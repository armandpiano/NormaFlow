<?php

declare(strict_types=1);

namespace App\UI\WEB\Controllers;

use App\Application\Services\CompanyService;
use App\UI\WEB\Requests\StoreCompanyRequest;
use App\UI\WEB\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct(
        private readonly CompanyService $companyService
    ) {}

    /**
     * Display list of companies
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', \App\Models\Company::class);

        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $companies = $this->companyService->getAllCompanies();
        } else {
            $companies = $this->companyService->getCompaniesByTenant(
                $user->tenantId->toString()
            );
        }

        return view('companies.index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show company details
     */
    public function show(Request $request, string $id): View
    {
        $company = $this->companyService->getCompany($id);
        
        if (!$company) {
            abort(404);
        }

        Gate::authorize('view', $company);

        $stats = $this->companyService->getCompanyStats($id);
        $sites = $this->companyService->getSitesByCompany($id);

        return view('companies.show', [
            'company' => $company,
            'stats' => $stats,
            'sites' => $sites,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        Gate::authorize('create', \App\Models\Company::class);

        return view('companies.create');
    }

    /**
     * Store new company
     */
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Company::class);

        $data = $request->validated();
        $data['tenant_id'] = Auth::user()->tenantId->toString();

        $company = $this->companyService->createCompany($data);

        return redirect()
            ->route('companies.show', $company->id->toString())
            ->with('success', 'Empresa creada correctamente.');
    }

    /**
     * Show edit form
     */
    public function edit(Request $request, string $id): View
    {
        $company = $this->companyService->getCompany($id);
        
        if (!$company) {
            abort(404);
        }

        Gate::authorize('update', $company);

        return view('companies.edit', [
            'company' => $company,
        ]);
    }

    /**
     * Update company
     */
    public function update(UpdateCompanyRequest $request, string $id): RedirectResponse
    {
        $company = $this->companyService->getCompany($id);
        
        if (!$company) {
            abort(404);
        }

        Gate::authorize('update', $company);

        $this->companyService->updateCompany($id, $request->validated());

        return redirect()
            ->route('companies.show', $id)
            ->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * Suspend company
     */
    public function suspend(Request $request, string $id): RedirectResponse
    {
        $company = $this->companyService->getCompany($id);
        
        if (!$company) {
            abort(404);
        }

        Gate::authorize('suspend', $company);

        $this->companyService->suspendCompany($id, $request->input('reason', 'Suspendido por administrador'));

        return redirect()
            ->route('companies.show', $id)
            ->with('success', 'Empresa suspendida correctamente.');
    }

    /**
     * Activate company
     */
    public function activate(string $id): RedirectResponse
    {
        $company = $this->companyService->getCompany($id);
        
        if (!$company) {
            abort(404);
        }

        Gate::authorize('activate', $company);

        $this->companyService->activateCompany($id);

        return redirect()
            ->route('companies.show', $id)
            ->with('success', 'Empresa activada correctamente.');
    }
}

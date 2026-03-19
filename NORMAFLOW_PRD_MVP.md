# NORMAFLOW MVP
## Product Requirements Document (PRD)

**Versión del Documento:** 1.0  
**Fecha:** 19 de marzo de 2026  
**Estado:** Borrador para revisión  
**Tipo de Documento:** PRD Completo del MVP

---

## TABLA DE CONTENIDOS

1. Visión del Producto
2. Problema Principal que Resuelve
3. Perfil del Cliente Ideal
4. Objetivo del MVP
5. Alcance del MVP (Incluye / Excluye)
6. Tipos de Usuario
7. Casos de Uso del Negocio
8. Módulos del MVP
9. Detalle de Funcionalidades por Módulo
10. Reglas de Negocio
11. Estados del Sistema
12. Dependencias entre Módulos
13. Riesgos Funcionales
14. Suposiciones del Producto
15. Definición de Éxito del MVP
16. Métricas Principales del Producto
17. Flujo General del Sistema
18. Consideraciones SaaS B2B Multiempresa

---

## 1. VISIÓN DEL PRODUCTO

### 1.1 Propuesta de Valor

**NormaFlow** es una plataforma SaaS que transforma la gestión de cumplimiento normativo operativo en un proceso sistemático, trazable y predecible. Permite a las empresas mexicanas y latinoamericanas mantener el control total sobre sus obligaciones regulatorias, desde la identificación de normativas aplicables hasta la generación de evidencias y el cierre de hallazgos.

### 1.2 Declaración de Misión

> "Democratizar el acceso al cumplimiento normativo operativo para empresas de todos los tamaños en Latinoamérica, eliminando la incertidumbre y el caos documental mediante tecnología inteligente y procesos automatizados."

### 1.3 Propuesta de Valor Única (PVU)

| Para | Que necesitan | NormaFlow ofrece | Que a diferencia de |
|------|---------------|------------------|---------------------|
| Coordinadores de Compliance | visibilidad total del estado de cumplimiento | dashboard en tiempo real con indicadores clave | spreadsheets y archivos dispersos |
| Directores de RH/Operaciones | cumplir auditorías sin crisis | checklist automatizado y alertas anticipadas |慌乱 y sobrecarga pre-auditoría |
| CEO/Propietarios | reducir riesgos legales y operativos | reportes ejecutivos con métricas de cumplimiento | incertidumbre sobre estado real |
| Auditores internos | obtener evidencias de forma rápida | repositorio centralizado con trazabilidad | buscar documentos en múltiples sistemas |

### 1.4 Elevator Pitch

"¿Cansado de descubrir que una NOM está vencida cuando ya es demasiado tarde? NormaFlow es el sistema que mantiene a tu empresa siempre lista para auditoría, con alertas automáticas, control documental y trazabilidad completa de cumplimiento normativo. En lugar de reaccionar, prepárate."

---

## 2. PROBLEMA PRINCIPAL QUE RESUELVE

### 2.1 El Problema

Las empresas en México y Latinoamérica enfrentan un entorno regulatorio complejo y en constante evolución:

- **+500 NOMs** aplicables a diferentes giros industriales
- **Múltiples autoridades** (STPS, SEMARNAT, COFEPRIS, etc.)
- **Vencimientos** que no se recuerdan hasta el día límite
- **Evidencias** dispersas en carpetas físicas, emails y dispositivos
- **Auditorías** que resultan en hallazgos evitables
- **Responsabilidad legal** personal para directivos

### 2.2 Pain Points Específicos

#### Pain Point 1: Desorganización Documental
**Síntoma:** "No sé dónde está la evidencia de capacitacion 2024"
- Documentos en múltiples ubicaciones (físico, SharePoint, email)
- Versiones desactualizadas
- Dificultad para demostrar qué existía en una fecha específica

**Impacto:** 
- Tiempo excesivo buscando documentos (4-8 horas/auditoría)
- Evidencias no disponibles cuando se necesitan
- Reproducción de documentos ya existentes

#### Pain Point 2: Falta de Seguimiento a Cumplimiento
**Síntoma:** "¿Ya vencemos esa NOM?"
- No existe visibilidad del estado de cumplimiento
- Dependencia de la memoria de personas específicas
- Cumplimiento solo se mide en el momento de auditoría

**Impacto:**
- Cumplimiento reactivo, no preventivo
- Hallazgos recurrentes
- Riesgo de sanciones y clausura

#### Pain Point 3: Vencimientos Sin Control
**Síntoma:** "Pensaba que teníamos otro año"
- Calendario de vencimientos no existe o está desactualizado
- No hay recordatorios automáticos
- Proceso de renovación iniciado tarde

**Impacto:**
- Vencimientos inadvertidos
- Penalizaciones por retraso
- Suspensión de operaciones en casos extremos

#### Pain Point 4: Auditorías Incompletas o Fallidas
**Síntoma:** "El auditor nos pidió cosas que no sabíamos que teníamos que tener"
- Preparación caótica y estresante
- Hallazgos por faltante de documentos
- Incertidumbre sobre alcance de auditorías

**Impacto:**
- Gastos en remediación urgente
- Daño reputacional
- Incremento en primas de seguro

#### Pain Point 5: Falta de Trazabilidad
**Síntoma:** "¿Quién modificó este documento?"
- No hay registro de cambios
- Imposible demostrar qué se hizo y cuándo
- Dificultad para responder a autoridades

**Impacto:**
- No cumplimiento con requerimientos de auditoría
- Incertidumbre en litigios
- No demostración de debida diligencia

### 2.3 Cuantificación del Problema

| Metric | Value | Source |
|--------|-------|--------|
| Costo promedio de una auditoría fallida | $150,000 - $500,000 MXN | Encuestas industria |
| Tiempo dedicado a buscar documentos pre-auditoría | 40-80 horas/año | Estimado interno |
| % de empresas que reprueban primera auditoría | 35% | STPS data |
| Costo de remediación post-auditoría | 2-5x costo de prevención | Best practices |
| Multas por incumplimiento NOM | Hasta $4.2M MXN | Ley Federal del Trabajo |

---

## 3. PERFIL DEL CLIENTE IDEAL

### 3.1 Perfil demográfico

| Attribute | Ideal Customer Profile (ICP) |
|-----------|------------------------------|
| **Industria** | Manufactura, Construcción, Servicios, Retail, Logística, Hospitalidad |
| **Tamaño** | Medianas empresas (50-500 empleados) |
| **Ubicación** | México (expansión posterior a LATAM) |
| **Giro regulatorio** | Alto: industria, salud, construcción, alimentos |
| **Madurez digital** | Intermedia: usa email, Office, possibly cloud |

### 3.2 Buyer Persona Primario

**Nombre:** Carlos Mendoza  
**Edad:** 42 años  
**Cargo:** Director de Recursos Humanos y Cumplimiento  
**Empresa:** Manufactura de autopartes, 200 empleados  
**Motivación:** Evitar sanciones, pasar auditorías sin crisis, demostrar cumplimiento a clientes corporativos  

**Frustraciones:**
- No tener visibilidad del estado de cumplimiento
- Preparar auditorías con panic mode
- Responsable legalmente pero sin herramientas

**Cómo escucha NormaFlow:**
- Recomendación de cámara empresarial
- Búsqueda Google "sistema cumplimiento NOM"
- Recomendación de consultor

### 3.3 Buyer Persona Secundario

**Nombre:** Patricia Sánchez  
**Edad:** 38 años  
**Cargo:** CEO / Proprietaria  
**Empresa:** Cadena de restaurantes, 8 sucursales, 150 empleados  
**Motivación:** Reducir riesgo legal personal, cumplir con clientes B2B que exigen certificación  

### 3.4 Criterios de Calificación

| Must Have | Nice to Have |
|-----------|--------------|
| 50+ empleados | +200 empleados |
| Al menos una sede con obligaciones NOM | Múltiples sedes |
| Proceso de auditoría anual | Auditorías trimestrales |
| Responsable de cumplimiento designado | Equipo de compliance |
| Presupuesto para herramientas SaaS | Budget para ERP |

### 3.5 Cuentas Objetivo Iniciales

**Segmento 1: Manufactura Industrial**
- Empresas con certificaciones de calidad (ISO)
- Clientes corporativos que exigen cumplimiento
- Presupuesto disponible

**Segmento 2: Construcción**
- Altísima rotación de personal
- Requisitos de STPS estrictos
- Múltiples obras/sedes

**Segmento 3: Hospitalidad y Restaurantes**
- COFEPRIS + NOMs laborales
- Expansión franchise
- Necesidad de estandarización

---

## 4. OBJETIVO DEL MVP

### 4.1 Propósito del MVP

El MVP de NormaFlow tiene como objetivo validar la propuesta de valor central del producto en un entorno controlado con clientes reales, midiendo adopción, satisfacción y disposición a pagar.

### 4.2 Objetivos Específicos

| Objetivo | Métrica | Target |
|----------|---------|--------|
| Validar necesidad | Empresas que usan activamente después de 30 días | 70% |
| Validar propuesta de valor | NPS score | ≥ 40 |
| Validar modelo de negocio | MRR | $50,000 MXN a 6 meses |
| Identificar feature gaps | Features solicitados en feedback | Top 5 priorizados |
| Estimar churn | Tasa de churn mensual | < 5% |

### 4.3 Horizonte de Tiempo

| Fase | Duración | Objetivo |
|------|----------|----------|
| Desarrollo MVP | 3 meses | Producto funcional |
| Beta cerrada | 2 meses | 5-10 clientes |
| Lanzamiento | Mes 6 | GO-TO-MARKET |
| Evaluación MVP | Mes 9 | Decisión sobre roadmap |

### 4.4 Budget Estimado MVP

| Concepto | Costo Estimado |
|----------|----------------|
| Desarrollo (3 devs x 3 meses) | $1,350,000 MXN |
| Infraestructura cloud | $50,000 MXN |
| Diseño UI/UX | $200,000 MXN |
| Marketing inicial | $100,000 MXN |
| Legal y admin | $50,000 MXN |
| Contingencia (20%) | $350,000 MXN |
| **Total MVP** | **$2,100,000 MXN** |

---

## 5. ALCANCE DEL MVP (INCLUYE / EXCLUYE)

### 5.1 Funcionalidades INCLUIDAS en MVP

#### Módulo: Gestión de Empresas y Tenants
- [x] Alta, edición, baja de empresa cliente (tenant)
- [x] Configuración de datos fiscales y contacto
- [x] Gestión de módulos activos por plan
- [x] Logo y branding básico

#### Módulo: Sedes / Centros de Trabajo
- [x] Alta, edición, baja de sedes
- [x] Datos de ubicación y contacto
- [x] Clasificación por tipo (matriz, sucursal, obra, etc.)
- [x] Asignación de responsables por sede

#### Módulo: Usuarios y Autenticación
- [x] Registro e inicio de sesión
- [x] Roles predefinidos (Admin, Editor, Viewer)
- [x] Asignación de permisos por módulo
- [x] Perfil de usuario
- [x] Recuperación de contraseña
- [x] Sesiones concurrentes controladas

#### Módulo: Matriz Normativa
- [x] Catálogo de NOMs/STPS con metadatos
- [x] Asignación de normatividad por sede
- [x] Clasificación por tipo (laboral, ambiental, salud, etc.)
- [x] Autoridad responsable
- [x] Estado de aplicación

#### Módulo: Requisitos
- [x] Alta de requisitos derivados de normatividad
- [x] Descripción y evidencia requerida
- [x] Frecuencia de cumplimiento (único, mensual, trimestral, anual)
- [x] Fecha de vigencia y vencimiento
- [x] Responsable asignado
- [x] Prioridad (crítica, alta, media, baja)
- [x] Estado del requisito (activo, vencido, cumplido, no aplicable)

#### Módulo: Evidencias Documentales
- [x] Carga de archivos (PDF, DOC, XLS, JPG, PNG)
- [x] Vinculación a requisito específico
- [x] Metadata: fecha de documento, tipo, descripción
- [x] Versionamiento de evidencias
- [x] Historial de cambios
- [x] Estados: pendiente, aprobado, rechazado
- [x] Búsqueda y filtros

#### Módulo: Vigencias y Alertas
- [x] Cálculo automático de fechas de vencimiento
- [x] Alertas por email (7, 30, 60, 90 días antes)
- [x] Dashboard de vencimientos próximos
- [x] Configuración de umbrales de alerta por empresa
- [x] Notificaciones in-app

#### Módulo: Hallazgos
- [x] Registro de hallazgos (internos o de auditoría)
- [x] Clasificación (menor, mayor, crítico)
- [x] Vinculación a requisito afectado
- [x] Evidencias fotográficas/documentales
- [x] Estados: abierto, en proceso, cerrado, verificado
- [x] Historial de cambios de estado

#### Módulo: Planes de Acción
- [x] Creación de plan de acción desde hallazgo
- [x] Tareas con responsable y fecha límite
- [x] Progreso百分比
- [x] Estados: pendiente, en progreso, completado, cancelado
- [x] Historial de avances

#### Módulo: Dashboard
- [x] Indicador global de cumplimiento (%)
- [x] Gráfico de cumplimiento por sede
- [x] Gráfico de cumplimiento por normatividad
- [x] Lista de vencimientos próximos
- [x] Lista de hallazgos abiertos
- [x] Accesos rápidos a módulos principales

#### Módulo: Reportes
- [x] Reporte de cumplimiento por sede
- [x] Reporte de cumplimiento por normatividad
- [x] Reporte de hallazgos abiertos/cerrados
- [x] Reporte de vencimientos
- [x] Exportación a PDF
- [x] Exportación a Excel

### 5.2 Funcionalidades EXCLUIDAS del MVP

| Feature | Razón de Exclusión | Roadmap |
|---------|---------------------|---------|
| Workflows de aprobación personalizados | Complejidad excesiva | V1 |
| Integración con sistemas externos (ERP, RH) | Requiere validación de mercado primero | V2 |
| App móvil nativa | Prioridad baja, web responsive es suficiente | V1 |
| Auditorías programadas con calendar integration | Depende de otras integraciones | V2 |
| Biblioteca de plantillas de evidencias | Requiere contenido curado | V1 |
| Generación automática de evidências | Requiere IA/ML | V2 |
| Marketplace de complementos | Ecosistema primero | V2 |
| Multiidioma (inglés) | Enfoque inicial en México | V1 |
| API pública | Seguridad primero | V2 |
| SSO / SAML | Solo empresas grandes lo necesitan | V1 |
| Wizard de importación de datos | Simplificar onboarding | V1 |
| Capacitación en línea (LMS) | Módulo separado | V2 |
|bitácora de auditoría avanzada | Sobrecarga técnica MVP | V1 |
|BI avanzado / Analytics predictivo | Requiere data volumen | V2 |
|Notificaciones SMS | Costo adicional, email suficiente | V1 |

---

## 6. TIPOS DE USUARIO

### 6.1 Roles Predefinidos

#### Rol: Super Administrador (Plataforma)
**Descripción:** Equipo interno de NormaFlow que gestiona la plataforma y clientes.

| Permission | Acceso |
|------------|--------|
| Gestionar tenants | Sí |
| Configurar catálogo de NOMs | Sí |
| Ver métricas de uso | Sí |
| Gestionar billing | Sí |
| Acceder a datos de tenant | No |

#### Rol: Administrador de Empresa (Tenant)
**Descripción:** Representante de la empresa cliente con control total sobre su cuenta.

| Permission | Acceso |
|------------|--------|
| Gestionar usuarios de su empresa | Sí |
| Gestionar sedes | Sí |
| Gestionar matrix normativa | Sí |
| Ver dashboard | Sí |
| Configurar alertas | Sí |
| Exportar reportes | Sí |
| Gestionar evidencias | Sí |
| Eliminar cuenta | Sí |

#### Rol: Coordinador de Compliance
**Descripción:** Responsable del programa de cumplimiento normativo.

| Permission | Acceso |
|------------|--------|
| Gestionar matrix normativa | Sí |
| Gestionar evidencias | Sí |
| Crear/editar hallazgos | Sí |
| Crear planes de acción | Sí |
| Ver dashboard | Sí |
| Exportar reportes | Sí |
| Gestionar usuarios | No |
| Eliminar evidencias | No |

#### Rol: Responsable Operativo
**Descripción:** Colaborador operativo responsable de cumplir requisitos específicos.

| Permission | Acceso |
|------------|--------|
| Ver requisitos asignados | Sí |
| Subir evidencias propias | Sí |
| Actualizar progreso de planes de acción | Sí |
| Ver dashboard (limitado) | Sí |
| Ver calendario de vencimientos | Sí |
| Gestionar matrix | No |
| Crear hallazgos | No |
| Ver información de otras sedes | Depende de configuración |

#### Rol: Invitado / Auditor Externo
**Descripción:** Usuario temporal para revisiones o auditorías.

| Permission | Acceso |
|------------|--------|
| Ver dashboard de cumplimiento | Solo lectura |
| Ver evidencias | Solo lectura |
| Crear hallazgos | Sí |
| Ver reportes | Solo lectura |
| Exportar datos | No |
| Modificar cualquier cosa | No |

### 6.2 Matriz de Permisos por Módulo

| Módulo | Super Admin | Admin Empresa | Coordinador | Responsable | Invitado |
|--------|-------------|---------------|-------------|-------------|----------|
| Empresas/Tenants | CRUD Propio | R Propio | - | - | - |
| Sedes | R Propio | CRUD Propio | R Propio | R Asignado | - |
| Usuarios | CRUD Propio | CRUD Propio | R Propio | - | - |
| Matriz Normativa | CRUD Global | CRUD Propio | CRUD Propio | R Asignado | R |
| Requisitos | CRUD Propio | CRUD Propio | CRUD Propio | RU Asignado | R |
| Evidencias | R Propio | CRUD Propio | CRUD Propio | RU Propio | R |
| Hallazgos | CRUD Propio | CRUD Propio | CRUD Propio | RU Asignado | C Propio |
| Planes de Acción | CRUD Propio | CRUD Propio | CRUD Propio | RU Asignado | R |
| Dashboard | R Global | R Propio | R Propio | R Propio | R |
| Reportes | R Global | R Propio | R Propio | R Propio | R |
| Configuración | CRUD Propio | CRUD Propio | - | - | - |

**Leyenda:** C=Create, R=Read, U=Update, D=Delete, -=Sin acceso

---

## 7. CASOS DE USO DEL NEGOCIO

### 7.1 CU-001: Onboarding de Nueva Empresa

**Actor:** Administrador de Empresa  
**Precondición:** Ninguna  
**Postcondición:** Empresa configurada con al menos una sede y un usuario adicional  

**Flujo Principal:**
1. Empresa se registra en plataforma
2. Proporciona datos fiscales y contacto
3. Selecciona plan (prueba o pagado)
4. Crea primera sede
5. Invita a usuarios adicionales
6. Configura alertas de notificación
7. Sistema presenta wizard de primeros pasos

**Flujo Alternativo:**
- Si empresa ya existe, adminsitrador solicita acceso
- Super Admin verifica y asigna permisos

**Requerimientos No Funcionales:**
- Registro < 10 minutos
- Onboarding completo < 30 minutos

---

### 7.2 CU-002: Configurar Matriz Normativa por Sede

**Actor:** Coordinador de Compliance  
**Precondición:** Sede creada, usuario autenticado con permisos  
**Postcondición:** Matriz normativa asignada a sede  

**Flujo Principal:**
1. Seleccionar sede
2. Acceder a "Matriz Normativa"
3. Sistema muestra catálogo de NOMs disponibles
4. Coordinador selecciona normatividad aplicable
5. Sistema crea requisitos derivados automáticamente
6. Coordinador asigna responsables a cada requisito
7. Define frecuencias y vencimientos
8. Guarda configuración

**Reglas de Negocio:**
- Cada requisito debe tener al menos un responsable
- Fechas de vencimiento se calculan según frecuencia
- Notificaciones se generan según configuración

---

### 7.3 CU-003: Subir Evidencia de Cumplimiento

**Actor:** Responsable Operativo  
**Precondición:** Requisito activo, usuario autenticado  
**Postcondición:** Evidencia vinculada a requisito, en estado "pendiente"  

**Flujo Principal:**
1. Seleccionar "Mis Requisitos"
2. Filtrar por estado o vencimiento
3. Seleccionar requisito específico
4. Revisar información del requisito
5. Clic en "Subir Evidencia"
6. Seleccionar archivo (PDF, DOC, XLS, JPG, PNG)
7. Completar metadata (fecha documento, descripción)
8. Subir archivo
9. Sistema muestra confirmación
10. Evidencia queda en estado "pendiente"

**Flujo Alternativo:**
- Si archivo es demasiado grande (>25MB), mostrar error
- Si formato no soportado, mostrar formatos válidos

**Reglas de Negocio:**
- Máximo 25MB por archivo
- Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG
- Evidencia debe vincularse a un requisito específico
- Fecha del documento no puede ser futura

---

### 7.4 CU-004: Aprobar o Rechazar Evidencia

**Actor:** Coordinador de Compliance  
**Precondición:** Evidencia en estado "pendiente"  
**Postcondición:** Evidencia aprobada o rechazada  

**Flujo Principal:**
1. Acceder a "Evidencias Pendientes"
2. Seleccionar evidencia
3. Revisar archivo y metadata
4. Decidir: aprobar o rechazar
5. Si rechaza, proporcionar comentario obligatorio
6. Sistema actualiza estado
7. Notifica a responsable

**Reglas de Negocio:**
- Rechazo requiere comentario obligatorio
- Aprobación cambia estado del requisito a "cumplido"
- Se registra fecha de aprobación

---

### 7.5 CU-005: Registrar Hallazgo de Auditoría

**Actor:** Coordinador de Compliance  
**Precondición:** Auditoría realizada o inspección interna  
**Postcondición:** Hallazgo creado y vinculado a requisito  

**Flujo Principal:**
1. Acceder a "Hallazgos"
2. Clic en "Nuevo Hallazgo"
3. Seleccionar tipo (auditoría externa, interna, autoevaluación)
4. Seleccionar sede donde se encontró
5. Vincular a requisito(s) afectado(s)
6. Clasificar severidad (menor, mayor, crítico)
7. Describir hallazgo con detalle
8. Agregar evidencias fotográficas si aplica
9. Guardar

**Reglas de Negocio:**
- Hallazgo siempre debe vincularse a al menos un requisito
- Clasificación determina SLA de resolución:
  - Crítico: 5 días
  - Mayor: 15 días
  - Menor: 30 días
- Notificación automática a responsable del requisito

---

### 7.6 CU-006: Crear Plan de Acción desde Hallazgo

**Actor:** Coordinador de Compliance  
**Precondición:** Hallazgo en estado "abierto"  
**Postcondición:** Plan de acción creado con tareas  

**Flujo Principal:**
1. Abrir detalle del hallazgo
2. Clic en "Crear Plan de Acción"
3. Sistema pre-puebla con datos del hallazgo
4. Agregar tareas al plan
5. Para cada tarea:
   - Descripción
   - Responsable
   - Fecha límite (sugerida según SLA)
6. Revisar y confirmar
7. Sistema envía notificaciones a responsables
8. Plan queda en estado "pendiente"

**Reglas de Negocio:**
- Cada plan debe tener al menos una tarea
- Fechas límite de tareas no pueden exceder SLA del hallazgo
- Responsable de tarea debe ser diferente al creador del plan

---

### 7.7 CU-007: Recibir Alerta de Vencimiento Próximo

**Actor:** Sistema (automático)  
**Precondición:** Requisito con vencimiento cercano según umbrales configurados  
**Postcondición:** Notificación enviada  

**Flujo Principal:**
1. Sistema ejecuta job nocturno
2. Identifica requisitos con vencimiento <= umbral configurado
3. Genera notificación por email e in-app
4. Incluye: requisito, sede, días restantes, acción requerida
5. Registra envío de notificación
6. Repite según frecuencia configurada

**Reglas de Negocio:**
- Umbrales por defecto: 90, 60, 30, 15, 7, 1 día(s)
- Empresa puede personalizar umbrales
- Notificaciones se envían una vez por día máximo
- No se envía si ya fue renovada evidencia

---

### 7.8 CU-008: Generar Reporte de Cumplimiento

**Actor:** Administrador de Empresa / Coordinador  
**Precondición:** Usuario autenticado con permisos  
**Postcondición:** Reporte generado en PDF o Excel  

**Flujo Principal:**
1. Acceder a "Reportes"
2. Seleccionar tipo de reporte
3. Seleccionar parámetros:
   - Periodo (fecha inicio, fecha fin)
   - Sede(s)
   - Normatividad
4. Clic en "Generar"
5. Sistema procesa datos
6. Presenta preview
7. Usuario elige formato (PDF o Excel)
8. Descarga archivo

**Reglas de Negocio:**
- Periodo máximo: 12 meses
- Descarga solo usuarios con rol Admin o Coordinador
- Archivo incluye metadata: empresa, generación, usuario

---

## 8. MÓDULOS DEL MVP

### 8.1 Mapa de Módulos

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           NORMAFLOW MVP                                 │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐                  │
│  │   USERS &   │    │  COMPANIES  │    │    SITES    │                  │
│  │ AUTH MODULE │◄──►│   MODULE    │◄──►│   MODULE    │                  │
│  └─────────────┘    └─────────────┘    └─────────────┘                  │
│         │                                      │                        │
│         ▼                                      ▼                        │
│  ┌─────────────────────────────────────────────────────────┐           │
│  │              COMPLIANCE CORE MODULE                     │           │
│  │  ┌───────────┐  ┌───────────┐  ┌───────────┐            │           │
│  │  │  MATRIX   │  │REQUIREMENT│  │ EVIDENCE  │            │           │
│  │  │  MODULE   │  │  MODULE   │  │  MODULE   │            │           │
│  │  └───────────┘  └───────────┘  └───────────┘            │           │
│  │  ┌───────────┐  ┌───────────┐  ┌───────────┐            │           │
│  │  │ FINDING   │  │  ACTION   │  │ VALIDITY  │            │           │
│  │  │  MODULE   │  │  PLAN     │  │ & ALERTS  │            │           │
│  │  │           │  │  MODULE   │  │  MODULE   │            │           │
│  │  └───────────┘  └───────────┘  └───────────┘            │           │
│  └─────────────────────────────────────────────────────────┘           │
│         │                                                       │       │
│         ▼                                                       ▼       │
│  ┌─────────────────────┐              ┌─────────────────────────┐      │
│  │    DASHBOARD        │              │      REPORTS           │      │
│  │     MODULE          │              │      MODULE             │      │
│  └─────────────────────┘              └─────────────────────────┘      │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Descripción de Módulos

| Módulo | Descripción | Complejidad |
|--------|-------------|-------------|
| **Users & Auth** | Autenticación, autorización, gestión de usuarios | Alta |
| **Companies** | Gestión de tenants, configuración multiempresa | Media |
| **Sites** | Centros de trabajo, ubicaciones | Media |
| **Matrix** | Catálogo normativo, asignación a sedes | Alta |
| **Requirements** | Requisitos derivados, seguimientos | Alta |
| **Evidence** | Gestión documental, versionamiento | Alta |
| **Findings** | Registro de hallazgos de auditoría | Media |
| **Action Plans** | Planes de acción y tareas | Media |
| **Validity & Alerts** | Control de vigencias, notificaciones | Alta |
| **Dashboard** | Indicadores, métricas visuales | Media |
| **Reports** | Generación de reportes ejecutivos | Alta |

---

## 9. DETALLE DE FUNCIONALIDADES POR MÓDULO

### 9.1 MÓDULO: USUARIOS Y AUTENTICACIÓN

#### Objetivo del Módulo
Proveer un sistema de autenticación seguro y gestión de usuarios con control de acceso basado en roles (RBAC) que soporte el modelo multi-tenant.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Login | Formulario email/contraseña, recordarme, olvidé contraseña |
| Registro | Formulario alta empresa + usuario admin |
| Perfil | Ver/editar datos personales |
| Gestión de Usuarios | Lista, crear, editar, eliminar usuarios |
| Roles y Permisos | Asignación de roles |

#### Campos por Pantalla

**Login:**
- Email (requerido, formato válido)
- Contraseña (requerido, mínimo 8 caracteres)
- Recordarme (checkbox opcional)

**Registro:**
- Nombre de empresa (requerido, 3-100 caracteres)
- RFC (requerido, formato válido México)
- Email contacto (requerido)
- Teléfono (opcional)
- Nombre usuario admin (requerido)
- Email usuario admin (requerido)
- Contraseña (requerido, mínimo 8 caracteres)
- Confirmar contraseña (requerido)

**Perfil Usuario:**
- Nombre completo
- Email
- Teléfono
- Puesto/Cargo
- Cambio de contraseña

**Gestión de Usuarios (Lista):**
- Tabla con: Nombre, Email, Rol, Estatus, Última sesión, Acciones
- Filtros: por rol, estatus
- Búsqueda por nombre/email
- Paginación (20 por página)

**Crear/Editar Usuario:**
- Nombre completo (requerido)
- Email (requerido, único en tenant)
- Rol (select)
- Sedes asignadas (multiselect)
- Enviar invitación (checkbox)

#### Funcionalidades Clave

| Funcionalidad | Descripción |
|---------------|-------------|
| Login con email/password | Autenticación con email institucional |
| Recuperação de contraseña | Envío de link por email, expiración 1 hora |
| Sesiones | Máximo 3 sesiones simultáneas por usuario |
| Login con Google | SSO con cuenta Google (OAuth 2.0) |
| Tokens de API | Para integraciones futuras |
| Logging de actividad | Registro de logins, logout, intentos fallidos |

#### Componentes UI

| Componente | Estados |
|------------|---------|
| Input field | default, focus, error, disabled |
| Button primary | default, hover, active, loading, disabled |
| Button secondary | default, hover, active, disabled |
| Data table | default, loading, empty, error |
| Modal | - |
| Toast notifications | success, error, warning, info |
| Avatar | default, placeholder, upload |
| Dropdown | default, open, disabled |
| Checkbox | unchecked, checked, indeterminate, disabled |
| Select/Singleselect | default, open, disabled, error |
| Multiselect | default, open, selected, disabled |
| Pagination | - |
| Spinner | - |
| Skeleton loader | - |

---

### 9.2 MÓDULO: EMPRESAS / TENANTS

#### Objetivo del Módulo
Gestionar la información de cada empresa cliente como tenant independiente, con configuraciones específicas de plan, billing y módulos activos.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Datos de Empresa | Información fiscal y contacto |
| Configuración | Módulos activos, umbrales de alerta |
| Plan y Billing | Plan actual, método de pago (informativo) |
| Integraciones | Conexiones con sistemas externos (futuro) |

#### Campos

**Datos de Empresa:**
- Nombre legal (requerido)
- Nombre comercial (opcional)
- RFC (requerido, formato válido)
- Calle, Número, Colonia (requerido)
- Ciudad, Estado, CP (requerido)
- Teléfono principal (requerido)
- Email de facturación (requerido)
- Logo (opcional, máx 2MB, PNG/JPG)

**Configuración General:**
- Zona horaria (select, default: Ciudad de México)
- Formato de fecha (DD/MM/AAAA)
- Moneda (MXN por defecto)
- Idioma (ES por defecto, futuro: EN)

**Configuración de Alertas:**
- Umbrales de notificación (días antes de vencimiento)
- Email principal para notificaciones
- Notificaciones in-app (toggle)

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| CRUD empresa | Alta, consulta, edición, baja lógica |
| Cambio de plan | Upgrade/downgrade de plan |
| Suspensión | Por falta de pago, admin puede revertir |
| Exportar datos | Download de todos los datos en JSON |

---

### 9.3 MÓDULO: SEDES / CENTROS DE TRABAJO

#### Objetivo del Módulo
Gestionar las ubicaciones físicas de la empresa donde se realiza actividad laboral y aplican obligaciones normativas específicas.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Lista de Sedes | Tabla con todas las sedes, filtros, búsqueda |
| Detalle de Sede | Información completa y tabs de submódulos |
| Crear/Editar Sede | Formulario de alta/modificación |

#### Campos

**Sede:**
- Nombre (requerido, único en empresa)
- Tipo: Matriz, Sucursal, Obra, Almacén, Planta, Oficina, Otro
- Calle, Número, Colonia (requerido)
- Ciudad, Estado, CP (requerido)
- Coordenadas GPS (lat/long, opcional)
- Teléfono (opcional)
- Email contacto (opcional)
- Activo/Inactivo (boolean)
- Responsable principal (FK usuario)
- Fecha de apertura (date)
- Empleados aprox. (number)

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| CRUD sedes | Alta, consulta, edición, baja lógica |
| Asignación de responsables | Uno o más usuarios por sede |
| Duplicar sede | Copiar configuración de normatividad |
| Reporte de sedes | Lista para exportar |

---

### 9.4 MÓDULO: MATRIZ NORMATIVA

#### Objetivo del Módulo
Mantener un catálogo actualizado de normatividad aplicable y permitir su asignación a sedes específicas.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Catálogo Normas | Lista de NOMs/STPS disponibles |
| Asignación por Sede | Matriz de Normas vs Sedes |
| Normas Aplicables | Vista filtrada por sede |

#### Catálogo de Normas (Data Model)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | UUID | Identificador único |
| clave | string | Clave oficial (ej: NOM-035-STPS-2018) |
| titulo | string | Título oficial completo |
| descripcion | text | Descripción/resumen |
| autoridad | enum | STPS, SEMARNAT, COFEPRIS, etc. |
| tipo | enum | Laboral, Ambiental, Salud, Seguridad |
| url_oficial | url | Link a DOF |
| fecha_publicacion | date | Fecha publicación DOF |
| fecha_vigor | date | Fecha entrada en vigor |
| vigencia_default | int | Meses de vigencia por defecto |
| activa | boolean | Si está activa en plataforma |

#### Campos - Asignación

| Campo | Tipo | Descripción |
|-------|------|-------------|
| sede_id | FK | Sede |
| norma_id | FK | Norma del catálogo |
| fecha_aplicacion | date | Cuándo inicia aplicación |
| vigente_desde | date | Fecha inicio vigencia actual |
| proxima_vigencia | date | Fecha vencimiento (calculada) |
| observaciones | text | Notas específicas |

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| CRUD catálogo | Admin plataforma |
| Asignar norma a sede | Crear vínculo norma-sede |
| Importar matriz Excel | Carga masiva desde Excel |
| Búsqueda de normas | Por clave, título, autoridad |
| Historial de cambios | Quién y cuándo modificó |

---

### 9.5 MÓDULO: REQUISITOS

#### Objetivo del Módulo
Gestionar los requisitos específicos derivados de cada norma asignada, con seguimiento de cumplimiento y vencimientos.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Lista de Requisitos | Vista general con filtros avanzados |
| Detalle de Requisito | Información completa y evidencias |
| Crear/Editar Requisito | Formulario de requisitos manual |

#### Campos

**Requisito:**
- id (UUID)
- sede_id (FK, requerido)
- norma_id (FK)
- titulo (string, requerido)
- descripcion (text)
- evidencia_requerida (text, qué documento se espera)
- frecuencia: Unico, Mensual, Trimestral, Semestral, Anual
- prioridad: Critica, Alta, Media, Baja
- fecha_vencimiento (date)
- estado: Activo, Cumplido, Vencido, No Aplicable
- responsable_id (FK usuario)
- creado_por (FK usuario)
- fecha_creacion (datetime)
- fecha_actualizacion (datetime)

#### Estados del Requisito

```
[Activo] ──► [Cumplido] (cuando se aprueba evidencia)
    │
    ├──► [Vencido] (cuando pasa fecha sin evidencia)
    │
    └──► [No Aplicable] (manual, con justificación)
```

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| CRUD requisitos | Alta manual o desde norma |
| Actualización masiva | Editar frecuencia, responsable |
| Importar de Excel | Carga masiva |
| Cálculo automático de vencimiento | Basado en frecuencia |
| Vista "Mis Requisitos" | Por responsable |
| Filtros avanzados | Por sede, norma, estado, prioridad |

---

### 9.6 MÓDULO: EVIDENCIAS DOCUMENTALES

#### Objetivo del Módulo
Centralizar la gestión documental vinculada a requisitos, con versionamiento, estados de aprobación y trazabilidad.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Lista de Evidencias | Galería/lista con filtros |
| Detalle de Evidencia | Preview archivo + metadata |
| Subir Evidencia | Formulario de carga |

#### Campos

**Evidencia:**
- id (UUID)
- requisito_id (FK, requerido)
- titulo (string, requerido)
- descripcion (text)
- archivo_url (string, path storage)
- archivo_nombre_original (string)
- archivo_tipo (enum: PDF, DOC, XLS, IMG)
- archivo_tamano (int, bytes)
- fecha_documento (date, fecha del documento físico)
- version (int)
- estado: Pendiente, Aprobado, Rechazado
- comentario_revision (text)
- aprobado_por (FK usuario)
- fecha_aprobacion (datetime)
- creado_por (FK usuario)
- fecha_creacion (datetime)

#### Estados y Transiciones

```
[Pendiente] ──► [Aprobado] ──► [Rechazado] (nueva versión)
     ▲              │
     └──────────────┘
     
[Rechazado] puede generar nueva evidencia que referencie la rechazada
```

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| Carga de archivos | Drag & drop, hasta 25MB |
| Preview integrado | PDF, imágenes, para otros mostrar icono |
| Versionamiento | Nueva versión mantiene historial |
| Vinculación a requisito | Uno o más requisitos |
| Estados de aprobación | Workflow simple |
| Historial de cambios | Todas las versiones |
| Búsqueda full-text | En títulos y descripciones |
| Etiquetas/Tags | Categorización adicional |

---

### 9.7 MÓDULO: VIGENCIAS Y ALERTAS

#### Objetivo del Módulo
Controlar las fechas de vencimiento de requisitos y generar alertas automáticas para garantizar cumplimiento preventivo.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Calendario de Vencimientos | Vista mensual/-semanal |
| Dashboard de Alertas | Lista priorizada de vencimientos |
| Configuración de Alertas | Umbrales por empresa |

#### Campos

**Configuración de Alertas (por Empresa):**
- dias_antes: [90, 60, 30, 15, 7, 1]
- email_notificaciones: email@empresa.com
- habilitado: boolean

**Registro de Alerta:**
- id (UUID)
- requisito_id (FK)
- tipo: Vencimiento, Documento Rechazado, Hallazgo Abierto
- dias_restantes: int
- enviada: boolean
- fecha_envio: datetime

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| Cálculo automático | Vencimiento = fecha_anterior + frecuencia |
| Job nocturno | Verificación diaria automática |
| Email automático | Según umbrales configurados |
| Notificaciones in-app | Centro de notificaciones |
| Recordatorio configurable | Frecuencia de recordatorios |
| Historial de notificaciones | Qué se envió y cuándo |

---

### 9.8 MÓDULO: HALLAZGOS

#### Objetivo del Módulo
Registrar y dar seguimiento a hallazgos encontrados en auditorías o inspecciones, clasificándolos por severidad.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Lista de Hallazgos | Con filtros por estado, severidad |
| Detalle de Hallazgo | Info completa + plan de acción |
| Crear Hallazgo | Formulario de registro |

#### Campos

**Hallazgo:**
- id (UUID)
- empresa_id (FK)
- sede_id (FK, requerido)
- requisito_id (FK, requerido)
- tipo: Auditoria Externa, Auditoria Interna, Autoevaluacion, Inspeccion
- titulo (string, requerido)
- descripcion (text, requerido)
- severidad: Critico, Mayor, Menor
- estado: Abierto, En Proceso, Cerrado, Verificado
- auditor_externo (string, opcional)
- fecha_deteccion (date)
- fecha_cierre (date)
- cerrado_por (FK usuario)
- creado_por (FK usuario)
- evidencia_urls: [string] (archivos adjuntos)

#### SLA por Severidad

| Severidad | días para cerrar |
|-----------|------------------|
| Crítico | 5 días |
| Mayor | 15 días |
| Menor | 30 días |

#### Estados

```
[Abierto] ──► [En Proceso] ──► [Cerrado] ──► [Verificado]
     │              │
     │              └─► puede volver a [Abierto]
     │
     └─► puede generar Plan de Acción
```

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| CRUD hallazgos | Alta manual |
| Clasificación por severidad | Con SLA automático |
| Vinculación a requisitos | Uno o más |
| Adjuntar evidencias | Fotos, documentos |
| Crear plan de acción | Desde detalle de hallazgo |
| Timeline de cambios | Historial completo |
| Notificaciones | A responsable de sede |

---

### 9.9 MÓDULO: PLANES DE ACCIÓN

#### Objetivo del Módulo
Gestionar planes de acción derivados de hallazgos, con tareas específicas, responsables y seguimiento de progreso.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Lista de Planes | Con filtros por estado, hallazgo |
| Detalle de Plan | Tareas y progreso |
| Crear/Editar Plan | Formulario de creación |

#### Campos

**Plan de Acción:**
- id (UUID)
- hallazgo_id (FK, requerido)
- titulo (string, requerido)
- descripcion (text)
- estado: Pendiente, En Progreso, Completado, Cancelado
- fecha_inicio (date)
- fecha_limite (date)
- creado_por (FK usuario)
- fecha_creacion (datetime)

**Tarea:**
- id (UUID)
- plan_id (FK, requerido)
- titulo (string, requerido)
- descripcion (text)
- responsable_id (FK usuario)
- fecha_limite (date)
- estado: Pendiente, En Progreso, Completado
- progreso (int, 0-100%)
- completado_en (datetime)
- orden (int)

#### Estados

```
[Pendiente] ──► [En Progreso] ──► [Completado]
                                    └─► [Cancelado]
```

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| CRUD planes | Alta, edición, cancelación |
| CRUD tareas | Agregar/quitar tareas |
| Asignación responsable | Por tarea |
| Seguimiento de progreso | % automático según tareas |
| Notificaciones | Recordatorios de fechas límite |
| Vincular evidencias | A tareas o al plan |

---

### 9.10 MÓDULO: DASHBOARD

#### Objetivo del Módulo
Proporcionar una vista consolidada del estado de cumplimiento con indicadores clave en tiempo real.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Dashboard Principal | Vista consolidada |
| Dashboard por Sede | Drill-down por ubicación |

#### Widgets

| Widget | Tipo | Descripción |
|--------|------|-------------|
| Indicador Global | KPI Card | Porcentaje cumplimiento total |
| Cumplimiento por Sede | Bar Chart | Comparativa entre sedes |
| Cumplimiento por Norma | Pie Chart | Distribución por tipo |
| Vencimientos Próximos | List | Siguientes 10 vencimientos |
| Hallazgos Abiertos | List | Por severidad |
| Actividad Reciente | Timeline | Últimas 20 acciones |

#### Métricas del Dashboard

| Métrica | Cálculo |
|---------|---------|
| % Cumplimiento Total | (Requisitos cumplidos / Total requisitos activos) × 100 |
| % Cumplimiento por Sede | Mismo cálculo filtrado por sede |
| Total Requisitos Activos | Count de requisitos con estado "Activo" |
| Total Vencidos | Count de requisitos vencidos sin evidencia |
| Total Hallazgos Abiertos | Count de hallazgos con estado ≠ "Verificado" |
| días hasta próximo vencimiento | Mínimo de (fecha_vencimiento - hoy) |

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| Refresh en tiempo real | Datos actualizados al cargar |
| Drill-down | Click en métrica abre detalle |
| Periodo configurable | Últimos 30, 90, 365 días |
| Exportar vista | PNG del dashboard |

---

### 9.11 MÓDULO: REPORTES

#### Objetivo del Módulo
Generar reportes ejecutivos y operativos en formatos exportables para presentación a auditoría o dirección.

#### Pantallas

| Pantalla | Descripción |
|----------|-------------|
| Catálogo de Reportes | Lista de reportes disponibles |
| Generador | Formulario con parámetros |
| Historial | Reportes generados anteriormente |

#### Tipos de Reporte

| Reporte | Descripción | Parámetros |
|---------|-------------|-------------|
| Cumplimiento por Sede | Estado de cumplimiento por ubicación | Sede(s), Periodo |
| Cumplimiento por Norma | Estado por normatividad | Norma(s), Periodo |
| Evidencias | Listado de evidencias cargadas | Sede, Estado, Tipo |
| Hallazgos | Estado de hallazgos | Sede, Severidad, Estado |
| Vencimientos | Calendario de próximos vencimientos | Dias futuro, Sede |
| Acciones | Progreso de planes de acción | Estado, Responsable |

#### Formatos de Exportación

| Formato | Uso Principal |
|---------|---------------|
| PDF | Presentación a dirección, auditoría |
| Excel | Análisis adicional, manipulación |

#### Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| Preview antes de descarga | Ver contenido |
| Filtros por periodo | Rango de fechas |
| Filtros por sede | Una o varias |
| Encabezado custom | Logo empresa, título |
| Metadatos | Fecha generación, usuario |
| Historial de reportes | Descargar nuevamente |

---

## 10. REGLAS DE NEGOCIO

### 10.1 Reglas de Gestión de Usuarios

| ID | Regla | Validación |
|----|-------|------------|
| RN-USER-001 | Email único por tenant | No puede existir otro usuario con mismo email |
| RN-USER-002 | Contraseña segura | Mínimo 8 caracteres, 1 mayúscula, 1 número |
| RN-USER-003 | Roles predefinidos | No se pueden crear roles custom en MVP |
| RN-USER-004 | Eliminación lógica | Usuario inactivo mantiene datos referenciados |

### 10.2 Reglas de Sedes

| ID | Regla | Validación |
|----|-------|------------|
| RN-SITE-001 | Nombre único por empresa | No puede existir otra sede con mismo nombre |
| RN-SITE-002 | Al menos una sede matriz | Cada empresa debe tener al menos una matriz |
| RN-SITE-003 | Baja lógica | Sede inactiva no aparece en selects pero mantiene historial |

### 10.3 Reglas de Matriz Normativa

| ID | Regla | Validación |
|----|-------|------------|
| RN-MATRIX-001 | Norma vigente | Solo normas con estatus "activa" pueden asignarse |
| RN-MATRIX-002 | Vigencia calculada | Proxima vigencia = fecha_anterior + frecuencia |
| RN-MATRIX-003 | Prevenir duplicados | No asignar misma norma a misma sede 2 veces |

### 10.4 Reglas de Requisitos

| ID | Regla | Validación |
|----|-------|------------|
| RN-REQ-001 | Requisito único por norma-sede | No duplicar requisito de misma norma en misma sede |
| RN-REQ-002 | Fecha vencimiento futura | No puede ser fecha pasada al crear |
| RN-REQ-003 | Responsable requerido | Todo requisito activo debe tener responsable |
| RN-REQ-004 | Cambio automático de estado | Estado cambia a "Vencido" al pasar fecha |

### 10.5 Reglas de Evidencias

| ID | Regla | Validación |
|----|-------|------------|
| RN-EVID-001 | Archivo requerido | Debo subir archivo |
| RN-EVID-002 | Tamaño máximo | 25MB por archivo |
| RN-EVID-003 | Formatos permitidos | PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG |
| RN-EVID-004 | Fecha documento no futura | No puede ser mayor a hoy |
| RN-EVID-005 | Vinculación requerida | Debe asociarse a al menos un requisito |
| RN-EVID-006 | Rechazo requiere justificación | Comentario obligatorio al rechazar |
| RN-EVID-007 | Aprobación cambia estado requisito | Al aprobar, requisito pasa a "Cumplido" |

### 10.6 Reglas de Hallazgos

| ID | Regla | Validación |
|----|-------|------------|
| RN-FIND-001 | Severidad determina SLA | Crítico: 5, Mayor: 15, Menor: 30 días |
| RN-FIND-002 | Requisito requerido | Debe vincularse al menos uno |
| RN-FIND-003 | Cierre requiere evidencia | Para cerrar, debe existir evidencia de remediación |

### 10.7 Reglas de Planes de Acción

| ID | Regla | Validación |
|----|-------|------------|
| RN-PLAN-001 | Al menos una tarea | Plan sin tareas no puede activarse |
| RN-PLAN-002 | Fecha límite tarea > hoy | Al crear tarea |
| RN-PLAN-003 | Responsable diferente | No puede ser el mismo que crea el plan |
| RN-PLAN-004 | Progreso automático | % = (tareas completadas / total tareas) × 100 |

### 10.8 Reglas de Alertas

| ID | Regla | Validación |
|----|-------|------------|
| RN-ALERT-001 | Umbrales por defecto | 90, 60, 30, 15, 7, 1 día(s) |
| RN-ALERT-002 | Solo una alerta por día | No spam de notificaciones |
| RN-ALERT-003 | Verificación nocturna | Job corre a las 2:00 AM (CDMX) |

---

## 11. ESTADOS DEL SISTEMA

### 11.1 Estados de Empresas

```
[Activa] ◄──► [En Prueba] ◄──► [Suspendida]
    │                              ▲
    │                              │
    └──────────► [Cancelada] ◄─────┘
```

| Estado | Descripción | Permisos |
|--------|-------------|----------|
| Activa | Cuenta operacional | Todos |
| En Prueba | Periodo trial (30 días) | Todos con limitaciones* |
| Suspendida | Por falta de pago | Solo lectura |
| Cancelada | Dado de baja | Ninguno |

*Límites en prueba: máx 1 sede, máx 5 usuarios

### 11.2 Estados de Sedes

| Estado | Descripción |
|--------|-------------|
| Activa | Operacional, aplica normatividad |
| Inactiva | Temporalmene sin operaciones |

### 11.3 Estados de Requisitos

```
[Activo] ──► [Cumplido] ──► [Activo] (nueva vigencia)
    │
    ├──► [Vencido]
    │
    └──► [No Aplicable]
```

| Estado | Trigger |
|--------|---------|
| Activo | Creado, sin evidencia aprobada |
| Cumplido | Evidencia aprobada por coordinador |
| Vencido | Pasó fecha sin evidencia aprobada |
| No Aplicable | Usuario marcó como no aplica con justificación |

### 11.4 Estados de Evidencias

| Estado | Transiciones |
|--------|--------------|
| Pendiente | Aprobado, Rechazado |
| Aprobado | - (cerrado) |
| Rechazado | Pendiente (nueva versión) |

### 11.5 Estados de Hallazgos

| Estado | Descripción |
|--------|-------------|
| Abierto | Nuevo, requiere acción |
| En Proceso | Plan de acción creado y en ejecución |
| Cerrado | Acciones completadas, pendiente verificación |
| Verificado | Auditoría/confirma cumplimiento |

### 11.6 Estados de Planes de Acción

| Estado | Descripción |
|--------|-------------|
| Pendiente | Creado pero no iniciado |
| En Progreso | Al menos una tarea en curso |
| Completado | Todas las tareas completadas |
| Cancelado | Ya no aplica |

### 11.7 Estados de Tareas

| Estado | Descripción |
|--------|-------------|
| Pendiente | Creada, sin iniciar |
| En Progreso | En ejecución |
| Completada | Finalizada |

---

## 12. DEPENDENCIAS ENTRE MÓDULOS

### 12.1 Mapa de Dependencias

```
┌─────────────┐
│   USERS     │ ◄── Dependencia base
└──────┬──────┘
       │
       ▼
┌─────────────┐      ┌─────────────┐
│  COMPANIES  │ ───► │   SITES     │
└──────┬──────┘      └──────┬──────┘
       │                    │
       │                    ▼
       │             ┌─────────────┐
       │             │MATRIX    │
       │             └──────┬──────┘
       │                    │
       │                    ▼
       │             ┌─────────────┐
       │             │ REQUIREMENTS│
       │             └──────┬──────┘
       │                    │
       ├────────────────────┤
       │                    │
       ▼                    ▼
┌─────────────┐      ┌─────────────┐
│  FINDINGS   │      │  EVIDENCES  │
└──────┬──────┘      └──────┬──────┘
       │                   │
       │                   │
       ▼                   │
┌─────────────┐             │
│ ACTION PLANS│             │
└──────┬──────┘             │
       │                   │
       │    ┌──────────────┘
       │    │
       ▼    ▼
┌─────────────────┐
│     ALERTS      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   DASHBOARD     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│    REPORTS      │
└─────────────────┘
```

### 12.2 Orden de Implementación

| Orden | Módulo | Dependencias |
|-------|--------|--------------|
| 1 | Users & Auth | Ninguna |
| 2 | Companies | Users |
| 3 | Sites | Users, Companies |
| 4 | Matrix | Sites |
| 5 | Requirements | Matrix, Sites |
| 6 | Evidence | Requirements, Users |
| 7 | Findings | Requirements, Sites |
| 8 | Action Plans | Findings |
| 9 | Alerts | Requirements, Findings |
| 10 | Dashboard | Todos |
| 11 | Reports | Todos |

### 12.3 APIs Internas entre Módulos

| De | A | Tipo de Dependencia |
|----|---|---------------------|
| Sites | Requirements | FK sede_id en requisito |
| Requirements | Evidence | FK requisito_id en evidencia |
| Requirements | Alerts | Consulta requisitos por vencer |
| Findings | Action Plans | FK hallazgo_id en plan |
| Action Plans | Tasks | Subtareas |
| Reports | Todos | Consulta datos agregados |

---

## 13. RIESGOS FUNCIONALES

### 13.1 Registro de Riesgos

| ID | Riesgo | Probabilidad | Impacto | Severidad | Mitigación |
|----|--------|--------------|---------|-----------|------------|
| RF-001 | Usuario no completa onboarding | Alta | Medio | Media | Wizard guiado, emails recordatorio |
| RF-002 | Carga inicial de datos muy alta | Media | Alto | Alta | Herramienta de importación Excel |
| RF-003 | Complejidad percibida del sistema | Alta | Alto | Crítica | UX simplificado, tooltips, onboarding |
| RF-004 | Módulos no utilizados | Alta | Medio | Media | Dashboard con acciones claras |
| RF-005 | Evidencias no vinculadas correctamente | Media | Alto | Alta | Validación en UI, tutoriales |
| RF-006 | Alertas no llegan (spam/filtros) | Media | Alto | Alta | Verificación de entregabilidad, logs |
| RF-007 | Datos incorrectos en catálogo NOMs | Baja | Alto | Media | Revisión periódica por equipo |
| RF-008 | Concurrencia en edición de evidencia | Baja | Bajo | Baja | Bloqueo optimista con advertencia |
| RF-009 | Pérdida de archivos subidos | Muy Baja | Crítico | Crítica | Backup diario, CDN confiable |
| RF-010 | No adopción por resistencia cultural | Media | Alto | Crítica | Capacitación, caso de éxito |

### 13.2 Plan de Mitigación Prioritario

**Riesgo Crítico: RF-003 Complejidad percibida**
1. Diseñar UX con principio de "progressive disclosure"
2. Mostrar solo módulos activos según plan
3. Primeros 3 módulos: Sedes → Matrix → Dashboard
4. Tooltips contextuales en cada campo
5. Videos cortos de capacitación (<3 min)
6. Checklist de primeros 7 días

**Riesgo Crítico: RF-010 Resistencia cultural**
1. Posicionar como "facilitador" no "controlador"
2. Casos de uso específicos por industria
3. Involucrar al equipo desde día 1
4. Mostrar beneficio individual (menos estrés en auditorías)

---

## 14. SUPUESTOS DEL PRODUCTO

### 14.1 Supuestos de Mercado

| ID | Supuesto | Validación |
|----|----------|------------|
| SM-001 | Empresas objetivo ya tienen obligaciones NOM | Verificar con prospección |
| SM-002 | Existe presupuesto para herramientas SaaS | Encuesta a prospects |
| SM-003 | Decisor es typically RRHH o Dirección | Entrevistas con buyers |
| SM-004 | Mercado mexicano acepta SaaS cloud | Tendencia observada |
| SM-005 | Competencia existente es manual (Excel) | Análisis de mercado |

### 14.2 Supuestos Técnicos

| ID | Supuesto | Validación |
|----|----------|------------|
| ST-001 | Disponibilidad de internet estable | Requisito de sistema |
| ST-002 | Navegadores modernos (Chrome, Firefox, Edge) | Target de compatibilidad |
| ST-003 | Archivos máximo 25MB son suficientes | Encuesta a usuarios |
| ST-004 | 5GB almacenamiento por tenant es suficiente | Estimación inicial |

### 14.3 Supuestos de Operaciones

| ID | Supuesto | Validación |
|----|----------|------------|
| SO-001 | Onboarding remoto es viable | A/B test con remoto vs on-site |
| SO-002 | Soporte por email es suficiente para MVP | NPS y tickets |
| SO-003 | Actualizaciones mensuales son aceptables | Roadmap de releases |

---

## 15. DEFINICIÓN DE ÉXITO DEL MVP

### 15.1 Criterios de Éxito

| Categoría | Métrica | Target | Método de Medición |
|-----------|---------|--------|---------------------|
| **Adopción** | Clientes activos | 10 empresas a 6 meses | DAU/MAU ratio |
| **Retención** | Churn rate mensual | < 5% | Analytics |
| **Utilización** | Requisitos cargados | > 100 requisitos/cliente | DB metrics |
| **Satisfacción** | NPS Score | ≥ 40 | Encuesta trimestral |
| **Revenue** | MRR | $50,000 MXN a 6 meses | Billing |
| **Engagement** | DAU/MAU | > 20% | Analytics |
| **Velocidad** | Tiempo onboarding | < 30 min | Funnel analytics |

### 15.2 Definition of Done (DoD)

El MVP se considera completo cuando:

- [ ] Todos los módulos implementados según alcance
- [ ] 0 bugs críticos o bloqueantes en producción
- [ ] Documentación de usuario disponible
- [ ] 3 beta customers usando activamente
- [ ] Pipeline de deployment automatizado
- [ ] Métricas de uso instrumentadas
- [ ] Proceso de soporte documentado
- [ ] Términos y condiciones legalmente revisados

### 15.3 Definition of Ready (DoR)

| Criterio | Descripción |
|----------|-------------|
| Requisitos claros | Historia de usuario con criterios de aceptación |
| Diseño aprobado | Wireframes/high-fidelity firmados |
| Criterios de prueba | Cómo se validará la funcionalidad |
| Dependencias resueltas | Módulos base completados |
|Estimación de esfuerzo | Story points asignados |

---

## 16. MÉTRICAS PRINCIPALES DEL PRODUCTO

### 16.1 Métricas de Negocio (North Star Metrics)

| Métrica | Definición | Target |
|---------|------------|--------|
| **NRR** (Net Revenue Retention) | Revenue mantenido + expansión - churn | > 100% |
| **LTV** (Lifetime Value) | Valor total del cliente | > $100,000 MXN |
| **CAC** (Customer Acquisition Cost) | Costo de obtener cliente | < $30,000 MXN |
| **LTV:CAC Ratio | Eficiencia de adquisición | > 3:1 |
| **MRR** | Ingresos mensuales recurrentes | $50K a mes 6 |

### 16.2 Métricas de Producto (Activity Metrics)

| Métrica | Definición | Target |
|---------|------------|--------|
| **MAU** | Usuarios únicos mensuales | 50 a mes 6 |
| **DAU** | Usuarios únicos diarios | 20 a mes 6 |
| **DAU/MAU** | Stickiness | > 20% |
| **Session Duration** | Tiempo por sesión | > 10 minutos |
| **Actions per Session** | Acciones por visita | > 5 |

### 16.3 Métricas de Engagement por Módulo

| Módulo | Métrica | Target |
|--------|---------|--------|
| Sedes | Avg. sedes por empresa | > 2 |
| Matriz | % empresas con norma asignada | > 80% |
| Requisitos | Avg. requisitos por empresa | > 50 |
| Evidencias | Evidencias cargadas/semana | > 10 |
| Hallazgos | % hallazgos cerrados < SLA | > 90% |
| Planes | % planes completados | > 70% |
| Dashboard | % usuarios que ven dashboard | > 60% |

### 16.4 Métricas de Salud del Sistema

| Métrica | Definición | Target |
|---------|------------|--------|
| Uptime | Disponibilidad del sistema | > 99.5% |
| Response Time | Tiempo de respuesta API | < 500ms |
| Error Rate | Errores 5xx / requests | < 0.1% |
| Support Tickets | Tickets abiertos / MAU | < 0.1 |

---

## 17. FLUJO GENERAL DEL SISTEMA (END-TO-END)

### 17.1 Flujo de Onboarding

```
┌─────────────────────────────────────────────────────────────────────┐
│                         ONBOARDING FLOW                             │
└─────────────────────────────────────────────────────────────────────┘

[Website/Referral]
        │
        ▼
[Registro Online]
    - Datos empresa
    - Datos usuario admin
    - Selección de plan
        │
        ▼
[Email Confirmación]
    - Verificar email
        │
        ▼
[Bienvenida + Wizard]
    1. Agregar Sede(s)
    2. Seleccionar Normas
    3. Agregar Usuarios
    4. Configurar Alertas
    5. Subir primeras evidencias
        │
        ▼
[Tutorial Interactivo]
    - Dashboard
    - Cómo subir evidencia
    - Cómo crear hallazgo
        │
        ▼
[Cuenta Activa - Trial 30 días]
```

### 17.2 Flujo de Cumplimiento Operativo

```
┌─────────────────────────────────────────────────────────────────────┐
│                    CUMPLIMIENTO OPERATIVO                          │
└─────────────────────────────────────────────────────────────────────┘

[Sistema]
    │
    ├──► [Job Nocturno]
    │         │
    │         ├──► Identifica vencimientos próximos
    │         │
    │         ├──► Genera alertas según umbrales
    │         │
    │         └──► Envía notificaciones email/in-app
    │
    ▼
[Responsable Operativo]
    │
    ├──► [Recibe Notificación]
    │         │
    │         ▼
    │    [Inicia Sesión]
    │         │
    │         ▼
    │    [Ve Dashboard - Alertas]
    │         │
    │         ▼
    │    [Accede a Requisito]
    │         │
    │         ▼
    │    [Sube Evidencia]
    │         │
    │         ▼
    │    [Estado: Pendiente]
    │
    ▼
[Coordinador de Compliance]
    │
    ├──► [Recibe Notificación]
    │         │
    │         ▼
    │    [Revisa Evidencia]
    │         │
    │         ├──► [Aprueba] ──► Requisito: Cumplido
    │         │
    │         └──► [Rechaza] ──► Requisito: Activo
    │                      └──► Notifica a Responsable
```

### 17.3 Flujo de Auditoría

```
┌─────────────────────────────────────────────────────────────────────┐
│                        AUDITORÍA FLOW                              │
└─────────────────────────────────────────────────────────────────────┘

[Auditor Externo / Interno]
    │
    ▼
[Solicita Evidencias]
    │
    ▼
[Coordinador de Compliance]
    │
    ├──► [Exporta Reporte Cumplimiento]
    │         │
    │         ▼
    │    [Prepara Expediente Digital]
    │         │
    │         ▼
    │    [Entrega Evidencias]
    │
    ▼
[Auditor Revisa]
    │
    ├──► [Sin Hallazgos]
    │         │
    │         ▼
    │    [Auditoría Aprobada] ──► Registro en sistema
    │
    └──► [Con Hallazgos] ──► [Registra en Sistema]
                               │
                               ▼
                          [Hallazgo Creado]
                               │
                               ▼
                          [Asigna Responsable]
                               │
                               ▼
                          [Crea Plan de Acción]
                               │
                               ▼
                          [Ejecuta Acciones]
                               │
                               ▼
                          [Cierra Hallazgo]
                               │
                               ▼
                          [Auditor Verifica]
                               │
                               ▼
                          [Hallazgo Verificado]
```

### 17.4 Flujo de Vencimiento

```
┌─────────────────────────────────────────────────────────────────────┐
│                       VENCIMIENTO FLOW                             │
└─────────────────────────────────────────────────────────────────────┘

[Día X-90] ──► [Alerta: 90 días]
[Día X-60] ──► [Alerta: 60 días]
[Día X-30] ──► [Alerta: 30 días]
[Día X-15] ──► [Alerta: 15 días] + [Dashboard: Warning]
[Día X-7]  ──► [Alerta: 7 días] + [Email a Admin]
[Día X-1]  ──► [Alerta: 1 día] + [SMS/Opcional]
[Día X]    ──► [Automático: Requisito Vencido]
                   │
                   ▼
              [Notificación: Vencido]
                   │
                   ▼
              [Sin Acción] ──► [Hallazgo automático opcional]
              [Con Acción] ──► [Subir evidencia de urgencia]
```

---

## 18. CONSIDERACIONES PARA SAAS B2B MULTIEMPRESA

### 18.1 Modelo de Multi-Tenancy

**Arquitectura:**
```
┌─────────────────────────────────────────────────────────────┐
│                      NORMAFLOW SaaS                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │
│   │Tenant A │  │Tenant B │  │Tenant C │  │Tenant N │       │
│   │Empresa 1│  │Empresa 2│  │Empresa 3│  │Empresa N│       │
│   └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘       │
│        │            │            │            │             │
│   ┌────┴────┐  ┌────┴────┐  ┌────┴────┐  ┌────┴────┐       │
│   │ Database│  │ Database│  │ Database│  │ Database│       │
│   │  Propio │  │  Propio │  │  Propio │  │  Propio │       │
│   └─────────┘  └─────────┘  └─────────┘  └─────────┘       │
│                                                             │
│   [CATÁLOGO COMPARTIDO: NOMs, Plantillas, Config Global]    │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Decisión de Diseño:** Database por tenant
- Aislamiento completo de datos
- Compliance más fácil
- Escalabilidad mediante partitionamiento futuro
- Trade-off: mayor costo de infraestructura

### 18.2 Aislamiento de Datos

| Aspecto | Implementación |
|---------|----------------|
| **Identificación** | Cada tenant tiene UUID único |
| **Acceso a datos** | Filtro automático por tenant_id en todas las queries |
| **Usuarios** | No pueden ver/aceder datos de otros tenants |
| **Archivos** | Bucket por tenant o prefijo por tenant |
| **Reportes** | Solo datos del tenant autenticado |

### 18.3 Modelos de Planes

| Plan | Características | Precio Sugerido |
|------|-----------------|-----------------|
| **Starter** | 1 sede, 5 usuarios, 1GB storage | $1,500/mes MXN |
| **Growth** | 5 sedes, 20 usuarios, 10GB storage | $4,500/mes MXN |
| **Corporate** | Sedes ilimitadas, usuarios ilimitados, 50GB | $12,000/mes MXN |

### 18.4 Billing y Pagos

| Aspecto | Implementación |
|---------|----------------|
| **Modelo** | SaaS subscription mensual/anual |
| **Métodos pago** | Transferencia, SPEI (inicial), Tarjeta (futuro) |
| **Trial** | 30 días, sin tarjeta |
| **Renovación** | Automática con recordatorio 7 días antes |
| **Suspensión** | 15 días después de fecha de pago fallida |

### 18.5 Compliance y Seguridad

| Requisito | Implementación |
|-----------|----------------|
| **Encriptación** | TLS en tránsito, AES-256 en reposo |
| **GDPR/MXF** | Protección de datos personales (LFPDPPP) |
| **Respaldo** | Daily backup, retention 30 días |
| **SLA** | 99.5% uptime, monitoreo 24/7 |
| **Auditoría** | Logs de acceso y modificaciones |

### 18.6 Internacionalización (Futuro)

| Aspecto | Implementación MVP | V2 |
|---------|-------------------|-----|
| **Moneda** | MXN | MXN, USD, COP, etc. |
| **Idioma** | Español | Español, Inglés |
| **Normativas** | NOMs México | Expandir a otros países |
| **Zona horaria** | México | Múltiples zonas |

---

## APÉNDICE A: GLOSARIO

| Término | Definición |
|---------|------------|
| **Tenant** | Instancia de empresa cliente en el sistema multi-tenant |
| **NOM** | Norma Oficial Mexicana |
| **STPS** | Secretaría del Trabajo y Previsión Social |
| **Cumplimiento** | Estado de haber satisfecho todos los requisitos normativos |
| **Evidencia** | Documento o archivo que demuestra cumplimiento |
| **Hallazgo** | No conformidad o deficiencia identificada en auditoría |
| **SLA** | Service Level Agreement - tiempo máximo para resolver |
| **MRR** | Monthly Recurring Revenue - ingresos mensuales recurrentes |
| **Churn** | Tasa de cancelación de clientes |
| **NPS** | Net Promoter Score - métrica de satisfacción |

---

## APÉNDICE B: REFERENCIAS NORMATIVAS

| Norma | Título | Autoridad |
|-------|-------|----------|
| NOM-035-STPS-2018 | Factores de riesgo psicosocial en el trabajo | STPS |
| NOM-024-STPS-2001 | Seguridad y funcionamiento de máquinas | STPS |
| NOM-030-STPS-2009 | Servicios preventivos de seguridad | STPS |
| NOM-001-SEDE-2012 | Instalaciones eléctricas | SE |
| NOM-052-SEMARNAT-2005 | Residuos peligrosos | SEMARNAT |

---

## APÉNDICE C: CASOS DE USO ADICIONALES

### CU-009: Importar Matriz desde Excel

**Actor:** Coordinador de Compliance  
**Flujo:**
1. Descarga plantilla Excel
2. Llena con normas y sedes
3. Sube archivo
4. Sistema valida formato
5. Preview de importación
6. Confirma importación
7. Sistema crea registros

### CU-010: Configurar Recordatorios Personalizados

**Actor:** Administrador de Empresa  
**Flujo:**
1. Accede a Configuración > Alertas
2. Selecciona umbrales
3. Agrega emails adicionales
4. Guarda configuración
5. Sistema actualiza job de notificaciones

---

## APÉNDICE D: ROADMAP POST-MVP

### Fase V1 (Meses 7-12)
- App móvil (iOS/Android)
- Integración Google Calendar
- Biblioteca de plantillas
- Workflow de aprobación custom
- Soporte multiidioma (inglés)

### Fase V2 (Meses 13-18)
- API pública para integraciones
- BI avanzado / Analytics
- Generación automática de evidencias (IA)
- Marketplace de complementos
- LMS de capacitación integrado

---

**Documento preparado por:** NormaFlow Product Team  
**Última actualización:** 19 de marzo de 2026  
**Versión:** 1.0

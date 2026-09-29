# ANEXO L  
# DOCUMENTACIÓN TÉCNICA COMPLEMENTARIA DEL DISPOSITIVO

## L.1 Introducción

Este anexo presenta la documentación técnica complementaria asociada al proceso de diseño e implementación del dispositivo tecnológico desarrollado para el registro, procesamiento y análisis de respuestas conductuales observables frente a estímulos sonoros en bebés de 0 a 6 meses.

La información presentada corresponde a elementos que, debido a su nivel de detalle técnico, fueron trasladados desde el documento principal con el propósito de mantener la estructura metodológica de la investigación sin extender innecesariamente el cuerpo de la monografía.

Este anexo incluye la especificación detallada de los requerimientos funcionales y no funcionales del dispositivo, los criterios de aceptación establecidos para su evaluación y las principales decisiones técnicas tomadas durante el desarrollo del prototipo.

---

# L.2 Requerimientos funcionales del dispositivo

Los requerimientos funcionales describen las capacidades que debe cumplir el dispositivo durante la ejecución de los ensayos experimentales. Estos requerimientos fueron definidos considerando los objetivos del proyecto, la arquitectura propuesta y las necesidades identificadas durante el desarrollo tecnológico.

| Código | Requerimiento | Descripción |
|---|---|---|
| RF-01 | Generación de estímulos sonoros | Permitir la reproducción de estímulos configurables según los parámetros definidos para cada ensayo. |
| RF-02 | Adquisición audiovisual | Registrar mediante video el comportamiento observable del bebé durante la presentación del estímulo. |
| RF-03 | Registro del ensayo | Almacenar información asociada al participante, sesión, estímulo y condiciones experimentales. |
| RF-04 | Procesamiento audiovisual | Extraer características cuantitativas relacionadas con movimientos cefálicos e indicadores faciales. |
| RF-05 | Clasificación operacional | Generar una clasificación del registro en respuesta conductual observable o respuesta no concluyente. |
| RF-06 | Gestión de información | Organizar los datos obtenidos para facilitar su consulta y revisión posterior. |
| RF-07 | Visualización mediante plataforma web | Permitir la consulta estructurada de registros por parte de usuarios autorizados. |
| RF-08 | Calibración inicial | Establecer una referencia inicial de orientación cefálica y condiciones faciales antes de cada ensayo, utilizada como línea base para comparar cambios posteriores. |
| RF-09 | Verificación de condiciones de adquisición | Comprobar la conexión de la cámara, encuadre, iluminación y visibilidad facial antes de iniciar el registro. |
| RF-10 | Configuración de parámetros del estímulo | Permitir configurar y registrar el tipo, frecuencia, duración, lateralidad y nivel relativo de salida del estímulo. |
| RF-11 | Consulta jerárquica por bebé, sesión y ensayo | Permitir consultar la información organizada por participante, sesión y ensayo. |
| RF-12 | Mantenimiento de trazabilidad integral | Conservar la correspondencia entre participante, sesión, ensayo, estímulo, archivo audiovisual, variables extraídas y clasificación generada. |

**Tabla L1. Requerimientos funcionales del dispositivo.**

---

# L.3 Requerimientos no funcionales del dispositivo

Los requerimientos no funcionales corresponden a características relacionadas con la calidad, operación y condiciones de uso esperadas del dispositivo desarrollado.

| Código | Requerimiento | Descripción |
|---|---|---|
| RNF-01 | No invasividad | Realizar la adquisición sin utilizar sensores adheridos al bebé. |
| RNF-02 | Trazabilidad | Mantener la relación entre estímulo, evidencia audiovisual, variables extraídas y clasificación generada. |
| RNF-03 | Protección de información | Gestionar los registros bajo criterios de privacidad y control de acceso. |
| RNF-04 | Modularidad | Permitir la integración y modificación independiente de los componentes del sistema. |
| RNF-05 | Reproducibilidad | Mantener condiciones configurables para comparar ensayos experimentales. |
| RNF-06 | Usabilidad | Facilitar la interacción con la plataforma según el perfil del usuario. |
| RNF-07 | Interpretación responsable | Presentar la salida computacional como información de apoyo y no como diagnóstico clínico. |
| RNF-08 | Interpretabilidad de las salidas | Utilizar categorías y variables coherentes con el alcance experimental del dispositivo. |
| RNF-09 | Control de parámetros del estímulo | Configurar y almacenar frecuencia, duración, lateralidad, secuencia y nivel relativo del estímulo. |
| RNF-10 | Calidad del registro audiovisual | Procurar iluminación suficiente, visibilidad facial, estabilidad del encuadre y continuidad del registro. |
| RNF-11 | Eficiencia en uso de recursos | Activar la vista previa de cámara únicamente cuando sea necesaria para verificar condiciones de adquisición. |
| RNF-12 | Accesibilidad tecnológica y económica | Priorizar componentes comerciales, herramientas de software accesibles y reutilización de equipos disponibles. |
| RNF-13 | Adaptabilidad operativa | Permitir ajustes en posición de cámara, ubicación del bebé y parámetros experimentales. |
| RNF-14 | Seguridad y bienestar del participante | Permitir interrupción del ensayo ante llanto persistente, fatiga, incomodidad o solicitud del cuidador. |
| RNF-15 | Portabilidad e integración física | Facilitar transporte e integración de componentes sin comprometer visibilidad, ventilación o conexiones esenciales. |

**Tabla L2. Requerimientos no funcionales del dispositivo.**

---

# L.4 Criterios de aceptación de requerimientos

Los criterios de aceptación fueron definidos para verificar que los requerimientos establecidos fueran implementados correctamente dentro del prototipo experimental.

| Requerimiento | Criterio de aceptación |
|---|---|
| Generación de estímulos sonoros | El sistema permite configurar y reproducir estímulos según los parámetros definidos para cada ensayo. |
| Adquisición audiovisual | El sistema obtiene registros audiovisuales donde sea posible identificar el comportamiento observable del participante. |
| Registro experimental | Cada ensayo conserva información asociada al participante, sesión y condiciones del estímulo. |
| Procesamiento audiovisual | El sistema genera variables cuantitativas derivadas del análisis del registro audiovisual. |
| Clasificación operacional | El modelo entrega una categoría correspondiente al ensayo procesado. |
| Gestión de información | La plataforma permite organizar y consultar los registros generados. |
| Trazabilidad | Cada resultado mantiene relación con el estímulo presentado, evidencia audiovisual y variables extraídas. |
| Protección de información | Los registros se gestionan mediante identificadores internos sin exposición directa de datos personales. |

**Tabla L3. Criterios de aceptación asociados a los requerimientos.**

---

# L.5 Decisiones técnicas durante el desarrollo del dispositivo

## L.5.1 Selección e integración de componentes

El desarrollo del dispositivo requirió una etapa inicial de análisis y selección de componentes tecnológicos considerando factores como disponibilidad comercial, compatibilidad, costo, capacidad de procesamiento y facilidad de integración.

La selección no estuvo limitada únicamente por las características individuales de cada componente, sino también por la necesidad de garantizar una comunicación adecuada entre los módulos de estimulación sonora, adquisición audiovisual, procesamiento computacional y almacenamiento.

---

## L.5.2 Limitación de interfaces físicas y adaptación de arquitectura

Durante la integración del hardware se identificó una restricción relacionada con la cantidad limitada de puertos disponibles en la plataforma embebida seleccionada.

Esta condición impedía conectar individualmente todos los componentes requeridos mediante interfaces independientes. Como solución tecnológica se reorganizó la arquitectura del sistema utilizando un dispositivo móvil como módulo auxiliar, permitiendo integrar funciones adicionales de adquisición y comunicación mediante una única interfaz de conexión.

Esta decisión permitió mantener la funcionalidad requerida del prototipo sin modificar completamente la plataforma principal de procesamiento.

---

## L.5.3 Desarrollo modular del sistema

La arquitectura final fue organizada mediante módulos independientes:

- **Módulo de estimulación sonora:** encargado de generar los estímulos configurados durante cada ensayo.
- **Módulo de adquisición audiovisual:** responsable de registrar el comportamiento observable del bebé.
- **Módulo de procesamiento computacional:** encargado de extraer características y ejecutar el modelo de clasificación.
- **Módulo de almacenamiento y visualización:** orientado a conservar la trazabilidad y facilitar la consulta de información.

La separación modular permitió realizar pruebas independientes y facilitar modificaciones futuras sobre componentes específicos.

---

# L.6 Escenarios detallados de uso

## L.6.1 Preparación del ensayo

Corresponde a la etapa previa a la adquisición de información.

Actividades principales:

- Configuración del dispositivo.
- Ubicación del participante.
- Verificación de condiciones audiovisuales.
- Configuración de parámetros del estímulo.
- Preparación del registro experimental.

Usuarios involucrados:

- Investigador.
- Especialista relacionado con el área auditiva.

---

## L.6.2 Ejecución experimental

Comprende la presentación del estímulo sonoro y captura del comportamiento observable.

Actividades principales:

- Reproducción del estímulo.
- Registro audiovisual.
- Almacenamiento del ensayo.
- Extracción inicial de información.

Usuarios involucrados:

- Investigador.
- Participante experimental.

---

## L.6.3 Consulta y revisión

Corresponde a la etapa posterior al procesamiento computacional.

Actividades principales:

- Consulta de registros.
- Visualización de evidencia audiovisual.
- Revisión de variables extraídas.
- Análisis de clasificación generada.

Usuario involucrado:

- Profesional relacionado con el área auditiva.

---

# L.7 Evolución de decisiones metodológicas

## L.7.1 Cambio de enfoque monocriterio a enfoque multimodal

Inicialmente el análisis del comportamiento se planteó considerando principalmente el movimiento cefálico como indicador observable posterior al estímulo sonoro.

Durante el desarrollo se identificó que los bebés entre 0 y 6 meses presentan variabilidad en el control cefálico y en la capacidad de realizar movimientos orientados claramente identificables.

Por esta razón, se incorporaron variables complementarias relacionadas con indicadores faciales y características temporales del comportamiento, permitiendo construir una representación multimodal del registro audiovisual.

## L.7.2 Adaptación de la arquitectura hardware

Durante la implementación se identificó una limitación relacionada con la cantidad disponible de interfaces físicas de la plataforma embebida.

La arquitectura inicial consideraba conexiones independientes para cada componente; sin embargo, debido a la restricción de puertos disponibles, fue necesario incorporar un dispositivo móvil como módulo auxiliar para integrar funcionalidades adicionales.

Esta decisión permitió conservar la funcionalidad requerida utilizando los recursos disponibles.

---

# L.8 Consideraciones finales

La documentación presentada en este anexo complementa la descripción metodológica del dispositivo desarrollado, permitiendo consultar los detalles técnicos sin afectar la continuidad del documento principal.

La especificación de requerimientos, decisiones de diseño y restricciones identificadas durante la implementación permiten conservar la trazabilidad del proceso de desarrollo y facilitan futuras modificaciones, replicaciones o ampliaciones del prototipo tecnológico.

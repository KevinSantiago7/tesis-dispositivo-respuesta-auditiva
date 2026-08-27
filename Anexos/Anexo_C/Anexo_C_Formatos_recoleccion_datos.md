# ANEXO C. FORMATOS DE RECOLECCIÓN DE DATOS

---

## C.1 Aclaración metodológica

La recolección de datos del dispositivo fue **completamente digital**: toda la información de caracterización, configuración del estímulo, parámetros del ensayo y evidencia audiovisual se capturó directamente a través de la interfaz gráfica del prototipo (Jetson Nano), sin formatos físicos en papel ni planillas independientes previas a la digitación. Tampoco existió un checklist físico o digital independiente para la verificación de condiciones ambientales; dicha verificación se realizó de forma visual por parte del operador, conforme al procedimiento descrito en el capítulo 3.

Este anexo presenta, por tanto, una reconstrucción formal de los **campos capturados por el sistema digital** en cada etapa del proceso de adquisición, organizados a manera de instrumento de recolección para facilitar su consulta y auditoría. No debe interpretarse como una reproducción de formularios físicos, ya que estos no existieron.

---

## C.2 Formato de registro de sesión y caracterización del participante

Campos capturados por el operador al crear una nueva sesión, de acuerdo con la Tabla 6 (sección 3.6.2):

| Campo | Tipo | Descripción / justificación |
|---|---|---|
| Código del bebé | Categórico | Identificador anonimizado; permite trazabilidad entre sesiones y ensayos sin usar datos de identidad directa. |
| Sexo biológico | Categórico | Caracterización descriptiva del participante. |
| Edad cronológica (meses) | Numérico | Permite comparaciones exploratorias según el rango etario. |
| Número de sesión | Numérico | Relaciona los ensayos realizados a un mismo bebé durante una jornada de adquisición. |
| Tipo del registro | Categórico | Permite diferenciar posteriormente registros experimentales reales y datos generados durante la etapa de entrenamiento computacional. |

---

## C.3 Formato de configuración del ensayo (parámetros del estímulo)

Campos capturados por el operador antes de iniciar cada ensayo, de acuerdo con la Tabla 7 (sección 3.6.3) y el Anexo B:

| Campo | Valores posibles | Descripción |
|---|---|---|
| Tipo de estímulo | Tono / sonido pregrabado | Define la naturaleza del estímulo sonoro presentado. |
| Frecuencia | 500, 1000, 2000 o 4000 Hz | Aplica únicamente a estímulos tipo tono. |
| Duración del estímulo | 1 segundo | Constante para todos los ensayos. |
| Lateralidad | Izquierda / derecha | Lado desde el cual se presenta el estímulo. |
| Nivel relativo de salida | 0,3 / 0,5 / 0,7 | Parámetro interno del reproductor; no corresponde a un valor calibrado en dB SPL. |

---

## C.4 Formato de identificación y trazabilidad del ensayo

Campos generados y almacenados automáticamente por el sistema al finalizar cada ensayo (sección 3.6.4 y 3.6.5):

| Campo | Descripción |
|---|---|
| `baby_id` | Identificador del bebé participante. |
| `session_id` | Identificador de la sesión de adquisición. |
| `trial_id` | Identificador único del ensayo dentro de la sesión. |
| Parámetros del estímulo utilizado | Tipo, frecuencia, duración, lateralidad y nivel relativo (ver C.3). |
| Evidencia audiovisual asociada | Referencia al archivo de video correspondiente al ensayo durante la etapa experimental |
| Marca temporal del ensayo | Fecha y hora de adquisición. |

---

## C.5 Estructura general del registro por ensayo (dataset consolidado)

Una vez procesado el video, cada ensayo se consolida como una fila del conjunto de datos, organizada en los siguientes grupos de variables (Tabla 9, sección 3.6.5):

| Grupo | Variables representativas | Finalidad |
|---|---|---|
| Identificación | `baby_id`, `session_id`, `trial_id` | Organización y trazabilidad. |
| Caracterización | Edad, sexo biológico | Descripción del participante. |
| Estímulo | Tipo, frecuencia, duración, lateralidad, nivel relativo | Documentación de las condiciones del ensayo. |
| Movimiento cefálico | Línea base, amplitud, velocidad, energía, variabilidad (incluye variables derivadas de movimiento cefálico (por ejemplo, variaciones angulares, amplitud, velocidad y medidas de dispersión), definidas en el Anexo E.) | Representación cuantitativa del movimiento registrado. |
| Indicadores faciales | Ojos, parpadeo, cejas, boca | Representación de cambios faciales. |
| Calidad audiovisual | Fotogramas válidos, proporción de detección facial | Control de calidad del registro. |
| Variable objetivo | Categoría conductual (0: no concluyente / 1: observable) | Clasificación binaria del ensayo. |

El diccionario completo y detallado de cada variable (nombre exacto, unidad, rango y descripción) se presenta en el **Anexo E**; este anexo (C) documenta los campos a nivel de instrumento de captura, no el detalle técnico de cada variable derivada.

---

## C.6 Relación con los criterios de aceptación

Los registros capturados mediante los formatos descritos en este anexo no se incorporaron automáticamente al conjunto de datos final. Su inclusión dependió del cumplimiento de los criterios de aceptación y descarte descritos en la sección **3.6.6**, aplicados después de la captura y antes del procesamiento y el entrenamiento del modelo.

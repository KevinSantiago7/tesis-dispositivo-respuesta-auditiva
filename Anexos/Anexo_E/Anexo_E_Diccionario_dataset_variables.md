# ANEXO E. DICCIONARIO DEL DATASET Y VARIABLES DEL MODELO DE CLASIFICACIÓN

---

## E.1 Descripción general del conjunto de datos

El conjunto de datos utilizado durante el desarrollo del modelo de clasificación fue construido a partir de los registros obtenidos durante las sesiones experimentales y de los datos generados como apoyo para la etapa de entrenamiento computacional.

Cada fila del dataset representa un ensayo experimental individual asociado a un participante, una sesión, un estímulo sonoro y un conjunto de características extraídas mediante el procesamiento audiovisual.

La estructura del dataset permite conservar la trazabilidad entre:

- identificación del participante,
- sesión experimental,
- ensayo realizado,
- condiciones del estímulo,
- variables derivadas del procesamiento,
- clasificación operacional generada por el modelo.

El dataset consolidado contiene variables de diferentes naturalezas: identificación, caracterización del participante, parámetros del estímulo, variables de procesamiento audiovisual, variables de control de calidad y características utilizadas por el modelo de clasificación.

Aunque el conjunto completo contiene múltiples variables asociadas al procesamiento y trazabilidad de los registros, el modelo final Random Forest utiliza únicamente un subconjunto de 23 características seleccionadas como variables de entrada.

---

# E.2 Estructura general del registro

Cada registro del dataset se organiza mediante los siguientes grupos de información:

| Grupo | Descripción |
|---|---|
| Identificación y trazabilidad | Variables utilizadas para relacionar participante, sesión y ensayo. |
| Caracterización del participante | Información descriptiva asociada al bebé registrado. |
| Configuración del estímulo | Parámetros correspondientes al sonido presentado durante el ensayo. |
| Procesamiento audiovisual | Variables obtenidas mediante análisis del registro de video. |
| Calidad del registro | Indicadores relacionados con disponibilidad y confiabilidad del procesamiento. |
| Variables del modelo | Características seleccionadas para el entrenamiento del clasificador. |
| Variable objetivo | Etiqueta utilizada para la clasificación supervisada. |

---

# E.3 Variables de identificación y trazabilidad

| Variable | Tipo | Descripción |
|---|---|---|
| `baby_id` | Categórica | Identificador anonimizado del participante. |
| `session_id` | Categórica | Identificador de la sesión experimental. |
| `trial_id` | Categórica | Identificador único del ensayo dentro de una sesión. |
| `unified_row_id` | Categórica | Identificador interno del registro consolidado. |

Estas variables permiten mantener la relación entre los datos originales, los ensayos realizados y las etapas posteriores de análisis.

---

# E.4 Variables de caracterización del participante

| Variable | Tipo | Descripción |
|---|---|---|
| Edad | Numérica | Edad cronológica del participante al momento del registro. |
| Sexo biológico | Categórica | Característica descriptiva del participante. |

Estas variables permiten realizar análisis exploratorios relacionados con las características generales de la muestra.

---

# E.5 Variables asociadas al estímulo experimental

| Variable | Tipo | Descripción |
|---|---|---|
| Tipo de estímulo | Categórica | Identifica la naturaleza del estímulo presentado. |
| Frecuencia | Numérica | Frecuencia del tono utilizado cuando corresponde. |
| Duración | Numérica | Tiempo de presentación del estímulo. |
| Lateralidad | Categórica | Lado desde el cual fue presentado el estímulo. |
| Nivel relativo de salida | Numérica | Parámetro interno de reproducción del estímulo; no corresponde a una medida calibrada en dB SPL. |

---

# E.6 Variables de entrada utilizadas por el modelo Random Forest

El modelo definitivo utilizó 23 variables de entrada multimodales relacionadas con movimiento cefálico y características faciales.

## E.6.1 Variables de movimiento cefálico

| Variable | Descripción |
|---|---|
| `baseline_yaw` | Valor inicial de orientación cefálica utilizado como referencia. |
| `mean_delta_yaw` | Cambio promedio de orientación cefálica respecto a la línea base. |
| `median_delta_yaw` | Mediana del cambio de orientación cefálica. |
| `std_delta_yaw` | Variabilidad del cambio de orientación cefálica. |
| `min_delta_yaw` | Cambio angular mínimo registrado. |
| `max_delta_yaw` | Cambio angular máximo registrado. |
| `range_delta_yaw` | Diferencia entre valores máximos y mínimos del desplazamiento angular. |

## E.6.2 Variables relacionadas con velocidad del movimiento

| Variable | Descripción |
|---|---|
| `mean_velocity` | Velocidad promedio del movimiento registrado. |
| `std_velocity` | Variabilidad de la velocidad del movimiento. |
| `max_velocity` | Velocidad máxima registrada. |
| `min_velocity` | Velocidad mínima registrada. |

## E.6.3 Variables de magnitud y distribución del movimiento

| Variable | Descripción |
|---|---|
| `peak_abs` | Magnitud máxima absoluta del movimiento. |
| `pct_negative` | Proporción de desplazamientos negativos. |
| `pct_positive` | Proporción de desplazamientos positivos. |
| `abs_mean_yaw` | Promedio absoluto del desplazamiento angular. |
| `abs_median_yaw` | Mediana absoluta del desplazamiento angular. |
| `movement_energy` | Medida asociada a la energía del movimiento durante el ensayo. |
| `samples` | Número de muestras utilizadas durante el procesamiento. |

## E.6.4 Variables de características faciales

| Variable | Descripción |
|---|---|
| `facial_score` | Indicador global de variación facial. |
| `blink_score` | Indicador asociado a cambios de parpadeo. |
| `eye_score` | Indicador asociado a la región ocular. |
| `eyebrow_score` | Indicador asociado a la región de cejas. |
| `mouth_score` | Indicador asociado a la región bucal. |

---

# E.7 Variables de calidad audiovisual y procesamiento

| Variable | Descripción |
|---|---|
| `valid_face_frames` | Número de fotogramas con detección facial válida. |
| `valid_face_ratio` | Proporción de fotogramas con detección facial disponible. |
| `fps` | Frecuencia de cuadros del registro audiovisual. |
| `total_frames_reported` | Número total de fotogramas registrados. |
| `duration_reported_s` | Duración reportada del video. |
| `reaction_time_s` | Tiempo asociado al cambio conductual identificado durante el procesamiento. |

Estas variables permiten evaluar las condiciones del registro y apoyar los criterios de selección de ensayos.

---

# E.8 Variables relacionadas con datos simulados

| Variable | Descripción |
|---|---|
| `data_origin` | Identifica si el registro corresponde a datos reales o simulados. |
| `synthetic_source_trial_id` | Relación del registro simulado con el ensayo de referencia utilizado. |
| `synthetic_method` | Método utilizado para generación del registro simulado. |
| `synthetic_seed` | Semilla utilizada para reproducibilidad del proceso de generación. |

Los registros simulados fueron utilizados únicamente como apoyo durante la etapa de entrenamiento del modelo y no como sustituto de observaciones reales durante la evaluación experimental final.

---

# E.9 Variable objetivo del modelo

| Variable | Tipo | Codificación |
|---|---|---|
| `response_behavior_class` | Binaria | 0 = respuesta no concluyente; 1 = respuesta conductual observable |

La variable objetivo representa una clasificación operacional basada en la evidencia conductual observable registrada durante el ensayo.

Esta salida computacional corresponde a información complementaria para la revisión del especialista y no constituye un diagnóstico clínico independiente.

---

# E.10 Relación entre dataset y modelo de clasificación

El dataset completo contiene información necesaria para la trazabilidad experimental, control de calidad y análisis posterior. Sin embargo, el modelo Random Forest emplea únicamente las 23 variables definidas en la sección E.6.

Esta separación permite diferenciar entre:

- variables necesarias para documentar y gestionar los registros experimentales;
- variables utilizadas directamente para el aprendizaje automático;
- variables asociadas a control de calidad y generación de datos simulados.

De esta manera se conserva la transparencia del flujo completo de datos, desde la adquisición audiovisual hasta la generación de la clasificación operacional.

# ANEXO E. DICCIONARIO DEL DATASET Y VARIABLES DEL MODELO DE CLASIFICACIÓN

---

# E.1 Descripción general del conjunto de datos

El conjunto de datos utilizado durante el desarrollo del modelo de clasificación fue construido a partir de los registros obtenidos durante las sesiones experimentales y de los datos generados como apoyo durante las etapas de entrenamiento computacional.

Cada fila del dataset representa un ensayo experimental individual asociado a un participante, una sesión, una configuración de estímulo y un conjunto de características extraídas mediante el procesamiento audiovisual.

La estructura del dataset permite conservar la trazabilidad entre:

- Participante.
- Sesión experimental.
- Ensayo realizado.
- Parámetros del estímulo presentado.
- Variables obtenidas mediante procesamiento audiovisual.
- Variables de control de calidad.
- Clasificación operacional generada por el modelo.

El conjunto completo contiene variables destinadas a diferentes propósitos: identificación y trazabilidad experimental, caracterización del participante, configuración del estímulo, procesamiento audiovisual, control de calidad y variables utilizadas directamente por el modelo de aprendizaje automático.

Aunque el dataset contiene información adicional necesaria para documentar y gestionar los registros experimentales, el modelo final Random Forest utiliza únicamente un conjunto seleccionado de **23 características de entrada**.

---

# E.2 Estructura general del registro

Cada registro del dataset se organiza mediante los siguientes grupos de información:

| Grupo | Descripción |
|---|---|
| Identificación y trazabilidad | Variables utilizadas para relacionar participante, sesión y ensayo experimental. |
| Caracterización del participante | Variables descriptivas asociadas al bebé registrado. |
| Configuración del estímulo | Parámetros relacionados con el sonido presentado durante el ensayo. |
| Procesamiento audiovisual | Variables obtenidas mediante análisis computacional del registro de video. |
| Calidad del registro | Variables utilizadas para verificar condiciones del procesamiento. |
| Variables del modelo | Características utilizadas como entrada del clasificador Random Forest. |
| Variable objetivo | Etiqueta utilizada para el aprendizaje supervisado. |

---

# E.3 Variables de identificación y trazabilidad

Estas variables permiten conservar la relación entre los registros originales y las etapas posteriores de procesamiento.

| Variable | Tipo | Descripción |
|---|---|---|
| `baby_id` | Categórica | Identificador interno asignado al participante para proteger su identidad y mantener la trazabilidad. |
| `session_id` | Categórica | Identificador correspondiente a la sesión experimental realizada. |
| `trial_id` | Categórica | Identificador individual del ensayo dentro de una sesión. |
| `unified_row_id` | Categórica | Identificador interno del registro consolidado dentro del dataset. |

---

# E.4 Variables de caracterización del participante

Estas variables permiten describir las características generales de la muestra experimental.

| Variable | Tipo | Descripción |
|---|---|---|
| Edad | Numérica | Edad cronológica del participante al momento del registro. |
| Sexo biológico | Categórica | Característica descriptiva del participante. |

Estas variables fueron utilizadas para caracterización de la muestra y análisis exploratorios.

---

# E.5 Variables asociadas al estímulo experimental

Estas variables describen las condiciones bajo las cuales fue presentado cada estímulo sonoro.

| Variable | Tipo | Descripción |
|---|---|---|
| Tipo de estímulo | Categórica | Identifica si corresponde a tono sintético o sonido pregrabado. |
| Frecuencia | Numérica | Frecuencia del estímulo tonal cuando aplica. |
| Duración | Numérica | Tiempo de presentación del estímulo. |
| Lateralidad | Categórica | Ubicación relativa de presentación del estímulo. |
| Nivel relativo de salida | Numérica | Parámetro interno de reproducción del sistema. No corresponde a una medición calibrada en dB SPL. |

---

# E.6 Variables de entrada utilizadas por el modelo Random Forest

El modelo final Random Forest utilizó **23 variables de entrada multimodales** obtenidas a partir del procesamiento audiovisual.

Estas variables fueron seleccionadas para representar características relacionadas con movimiento cefálico, dinámica temporal e indicadores faciales.

## E.6.1 Resumen de grupos de características

| Grupo de características | Número de variables |
|---|---:|
| Movimiento cefálico | 7 |
| Velocidad del movimiento | 4 |
| Magnitud y distribución del movimiento | 7 |
| Indicadores faciales | 5 |
| **Total** | **23** |

---

# E.6.2 Variables de movimiento cefálico

| Variable | Descripción |
|---|---|
| `baseline_yaw` | Valor inicial de orientación cefálica utilizado como referencia durante la línea base. |
| `mean_delta_yaw` | Cambio promedio de orientación cefálica respecto a la línea base. |
| `median_delta_yaw` | Mediana del cambio de orientación cefálica. |
| `std_delta_yaw` | Variabilidad del cambio de orientación cefálica. |
| `min_delta_yaw` | Cambio angular mínimo registrado durante el ensayo. |
| `max_delta_yaw` | Cambio angular máximo registrado durante el ensayo. |
| `range_delta_yaw` | Diferencia entre valores máximos y mínimos del desplazamiento angular. |

---

# E.6.3 Variables relacionadas con velocidad del movimiento

| Variable | Descripción |
|---|---|
| `mean_velocity` | Velocidad promedio del movimiento registrado. |
| `std_velocity` | Variabilidad de la velocidad del movimiento. |
| `max_velocity` | Velocidad máxima registrada durante el ensayo. |
| `min_velocity` | Velocidad mínima registrada durante el ensayo. |

---

# E.6.4 Variables de magnitud y distribución del movimiento

| Variable | Descripción |
|---|---|
| `peak_abs` | Magnitud máxima absoluta del movimiento registrado. |
| `pct_negative` | Proporción de desplazamientos negativos durante el ensayo. |
| `pct_positive` | Proporción de desplazamientos positivos durante el ensayo. |
| `abs_mean_yaw` | Promedio absoluto del desplazamiento angular. |
| `abs_median_yaw` | Mediana absoluta del desplazamiento angular. |
| `movement_energy` | Medida asociada a la energía del movimiento durante el ensayo. |
| `samples` | Número de muestras utilizadas durante el procesamiento. |

---

# E.6.5 Variables de características faciales

| Variable | Descripción |
|---|---|
| `facial_score` | Indicador global de variación facial registrado durante el ensayo. |
| `blink_score` | Indicador asociado a cambios relacionados con parpadeo. |
| `eye_score` | Indicador asociado a variaciones en la región ocular. |
| `eyebrow_score` | Indicador asociado a cambios en la región de cejas. |
| `mouth_score` | Indicador asociado a cambios en la región bucal. |

---

# E.7 Variables auxiliares de calidad audiovisual y procesamiento

Estas variables permiten evaluar las condiciones del registro y apoyar la selección de ensayos.

No corresponden a variables utilizadas directamente como entrada del modelo Random Forest.

| Variable | Descripción |
|---|---|
| `valid_face_frames` | Número de fotogramas con detección facial válida. |
| `valid_face_ratio` | Proporción de fotogramas con detección facial disponible. |
| `fps` | Frecuencia de cuadros del registro audiovisual. |
| `total_frames_reported` | Número total de fotogramas registrados. |
| `duration_reported_s` | Duración reportada del video. |
| `reaction_time_s` | Tiempo asociado al cambio conductual identificado durante el procesamiento. |

---

# E.8 Variables relacionadas con datos simulados

Durante el desarrollo del modelo se utilizaron registros reales y registros simulados como apoyo para la etapa de entrenamiento computacional.

Los registros simulados fueron diferenciados mediante variables específicas de origen y no fueron utilizados como sustituto de los registros reales durante la evaluación experimental final.

| Variable | Descripción |
|---|---|
| `data_origin` | Identifica si el registro corresponde a datos reales o simulados. |
| `synthetic_source_trial_id` | Relación del registro simulado con el ensayo de referencia utilizado. |
| `synthetic_method` | Método empleado para generar el registro simulado. |
| `synthetic_seed` | Semilla utilizada para reproducibilidad del proceso de generación. |

---

# E.9 Variable objetivo del modelo

La variable objetivo corresponde a la etiqueta utilizada durante el entrenamiento supervisado del modelo.

| Variable | Tipo | Codificación |
|---|---|---|
| `response_behavior_class` | Binaria | 0 = respuesta no concluyente; 1 = respuesta conductual observable |

Esta variable representa una clasificación operacional basada en la evidencia conductual observable registrada durante el ensayo.

La salida generada por el modelo corresponde a una herramienta de apoyo para organizar información experimental y no representa un diagnóstico clínico independiente.

---

# E.10 Relación entre dataset y modelo de clasificación

El dataset completo contiene información necesaria para conservar la trazabilidad experimental, documentar las condiciones de adquisición y analizar el comportamiento del sistema.

Sin embargo, el modelo Random Forest utiliza únicamente las 23 variables definidas como características de entrada.

Esta separación permite diferenciar entre:

- Variables destinadas a identificación y trazabilidad.
- Variables utilizadas directamente por el modelo.
- Variables auxiliares para control de calidad.
- Variables relacionadas con generación de datos simulados.

De esta manera se conserva la transparencia del flujo completo de información desde la adquisición audiovisual hasta la generación de la clasificación operacional.

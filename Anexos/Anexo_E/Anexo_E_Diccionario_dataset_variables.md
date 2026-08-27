# ANEXO E. DICCIONARIO DEL DATASET Y VARIABLES UTILIZADAS EN EL MODELO DE CLASIFICACIÓN

---

## E.1 Descripción general del conjunto de datos

El conjunto de datos utilizado para el desarrollo del modelo de clasificación fue estructurado a partir de registros organizados jerárquicamente por participante, sesión y ensayo. Cada fila del dataset representa un ensayo experimental individual asociado a un estímulo sonoro específico y a las características extraídas del procesamiento audiovisual del comportamiento observable del bebé.

La estructura del dataset permitió conservar la trazabilidad entre la identificación del participante, las condiciones experimentales, las variables derivadas del procesamiento computacional y la clasificación operacional generada por el modelo.

El modelo definitivo implementado corresponde a un clasificador **Random Forest**, entrenado utilizando un conjunto de variables multimodales relacionadas con movimiento cefálico, dinámica del movimiento y características faciales. La configuración final utilizó 23 variables de entrada definidas previamente para el entrenamiento del modelo.

---

## E.2 Estructura general del registro

| Grupo | Variables | Función dentro del dataset |
|---|---|---|
| Identificación | `baby_id`, `session_id`, `trial_id` | Permiten conservar la trazabilidad entre participante, sesión y ensayo. |
| Caracterización | Edad, sexo biológico | Descripción general del participante. |
| Estímulo | Tipo, frecuencia, duración, lateralidad, nivel relativo | Representan las condiciones bajo las cuales fue realizado el ensayo. |
| Variables audiovisuales | Características cefálicas y faciales | Representan cuantitativamente la respuesta observable extraída del video. |
| Variable objetivo | `response_behavior_class` | Etiqueta utilizada para el entrenamiento y evaluación del modelo. |

---

# E.3 Variables de entrada del modelo Random Forest

Las variables utilizadas como entrada del modelo fueron agrupadas según la fuente de información de la cual fueron obtenidas.

## E.3.1 Variables de movimiento cefálico

| Código | Variable | Descripción |
|---|---|---|
| X1 | `baseline_yaw` | Valor inicial de orientación cefálica utilizado como referencia durante el ensayo. |
| X2 | `mean_delta_yaw` | Promedio del cambio de orientación cefálica respecto a la línea base. |
| X3 | `median_delta_yaw` | Mediana del cambio de orientación cefálica. |
| X4 | `std_delta_yaw` | Variabilidad del cambio de orientación cefálica durante el registro. |
| X5 | `min_delta_yaw` | Valor mínimo del cambio angular registrado. |
| X6 | `max_delta_yaw` | Valor máximo del cambio angular registrado. |
| X7 | `range_delta_yaw` | Rango entre el valor máximo y mínimo del desplazamiento angular. |

Estas variables representan la dinámica del movimiento cefálico frente al estímulo presentado.

---

## E.3.2 Variables relacionadas con velocidad del movimiento

| Código | Variable | Descripción |
|---|---|---|
| X8 | `mean_velocity` | Velocidad promedio del movimiento cefálico registrado. |
| X9 | `std_velocity` | Variabilidad de la velocidad durante el ensayo. |
| X10 | `max_velocity` | Velocidad máxima alcanzada. |
| X11 | `min_velocity` | Velocidad mínima registrada. |

---

## E.3.3 Variables de magnitud y distribución del movimiento

| Código | Variable | Descripción |
|---|---|---|
| X12 | `peak_abs` | Magnitud máxima absoluta del movimiento registrado. |
| X13 | `pct_negative` | Proporción de desplazamientos negativos respecto al eje analizado. |
| X14 | `pct_positive` | Proporción de desplazamientos positivos respecto al eje analizado. |
| X15 | `abs_mean_yaw` | Promedio absoluto del desplazamiento angular. |
| X16 | `abs_median_yaw` | Mediana absoluta del desplazamiento angular. |
| X17 | `movement_energy` | Medida relacionada con la energía del movimiento durante el ensayo. |
| X18 | `samples` | Número de muestras utilizadas durante el procesamiento del registro. |

---

## E.3.4 Variables de respuesta facial

| Código | Variable | Descripción |
|---|---|---|
| X19 | `facial_score` | Indicador global de variación facial extraída del registro audiovisual. |
| X20 | `blink_score` | Medida asociada a cambios relacionados con parpadeo. |
| X21 | `eye_score` | Indicador de variación asociada a la región ocular. |
| X22 | `eyebrow_score` | Indicador de cambios asociados a la región de cejas. |
| X23 | `mouth_score` | Indicador de variación asociada a la región bucal. |

---

# E.4 Variable objetivo del modelo

| Variable | Tipo | Codificación |
|---|---|---|
| `response_behavior_class` | Binaria | 0 = respuesta no concluyente; 1 = respuesta conductual observable |

La clasificación generada representa una categoría operacional asociada a la evidencia conductual observada durante el ensayo y no corresponde a una determinación clínica independiente de la función auditiva.

---

# E.5 Configuración del modelo asociado al dataset

| Parámetro | Valor |
|---|---|
| Algoritmo | Random Forest Classifier |
| Número de árboles | 600 |
| Criterio | Gini |
| Profundidad máxima | 4 |
| División mínima | 5 |
| Mínimo de muestras por hoja | 2 |
| Máximo de variables evaluadas | √n |
| Balance de clases | Activado |

Estos parámetros corresponden a la configuración definitiva utilizada en el entrenamiento del modelo.

---

## Nota metodológica

Las variables descritas en este anexo corresponden exclusivamente a las características utilizadas en el modelo final de clasificación. La selección de estas variables permitió integrar información multimodal proveniente del movimiento cefálico y de indicadores faciales, manteniendo la trazabilidad entre el registro audiovisual original y la clasificación operacional generada por el sistema.

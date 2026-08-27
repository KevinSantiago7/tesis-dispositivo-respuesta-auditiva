# ANEXO F. VALIDACIÓN DE EFECTIVIDAD TÉCNICA, PERTINENCIA Y EVALUACIÓN DEL DISPOSITIVO

---

## F.1 Validación técnica del módulo de estímulo sonoro

### F.1.1 Objetivo de la prueba

La prueba tuvo como objetivo verificar la correspondencia entre los parámetros configurados internamente en el módulo de estímulo sonoro del dispositivo y las características reales de la señal reproducida por el sistema de audio.

La evaluación se centró en la verificación de la reproducción de frecuencias configuradas y la consistencia del estímulo generado antes de su utilización durante los ensayos experimentales.

Esta prueba corresponde a una **verificación técnica del desempeño del hardware dentro del contexto experimental del dispositivo**, por lo cual sus resultados no deben interpretarse como una calibración audiológica clínica ni como una certificación de niveles de presión sonora. Su finalidad fue comprobar que el módulo reproduce estímulos consistentes y cercanos a los parámetros definidos en el protocolo experimental.

---

# F.2 Instrumentación utilizada

| Instrumento | Marca / Modelo | Función en la prueba |
|---|---|---|
| Micrófono | [Completar referencia si aplica] | Captura de la señal acústica generada por el parlante y conversión a señal eléctrica. |
| Osciloscopio | Tektronix 2225 50MHz | Visualización de la señal capturada y medición del periodo entre picos consecutivos. |
| Parlante del dispositivo | [Completar referencia] | Elemento evaluado dentro del módulo de estimulación sonora. |

> Nota. La medición realizada corresponde a una verificación funcional de la señal reproducida. No corresponde a una medición audiológica certificada en dB SPL.

---

# F.3 Montaje experimental

La medición se realizó mediante la captura de la señal acústica emitida por el parlante del dispositivo y su visualización mediante un osciloscopio.

Las condiciones generales del montaje fueron:

- Fuente de estímulo: módulo de reproducción sonora del dispositivo.
- Elemento de captura: micrófono conectado al sistema de medición.
- Señales evaluadas: tonos sintéticos de 500 Hz, 1000 Hz, 2000 Hz y 4000 Hz.
- Registro de la señal: visualización de la forma de onda en el osciloscopio.

![Montaje experimental de la prueba de efectividad](./images/anexo_f_montaje.png)

*Figura F.1. Montaje utilizado para la verificación técnica del módulo de estímulo sonoro.*

---

# F.4 Procedimiento de medición

Para verificar la frecuencia realmente reproducida por el dispositivo se utilizó un micrófono conectado a un osciloscopio. La señal acústica generada por el parlante fue transformada en una señal eléctrica y representada como una onda en función del tiempo.

El procedimiento aplicado para cada estímulo fue:

1. Configurar el dispositivo con la frecuencia nominal correspondiente.
2. Reproducir el estímulo tonal seleccionado.
3. Capturar la señal generada en el osciloscopio.
4. Identificar dos picos consecutivos de la onda.
5. Medir el intervalo temporal entre ambos picos, correspondiente al periodo (T).
6. Calcular la frecuencia mediante:

\[
f = \frac{1}{T}
\]

donde:

- **f** corresponde a la frecuencia calculada en Hz.
- **T** corresponde al periodo medido en segundos.

El periodo fue determinado mediante:

\[
T = número\ de\ divisiones \times escala\ temporal\ (sec/div)
\]

La frecuencia obtenida fue comparada con la frecuencia nominal configurada en el dispositivo.

---

# F.5 Resultados de la validación técnica

## F.5.1 Verificación de frecuencia

| Estímulo | Frecuencia nominal (Hz) | Periodo medido T (s) | Frecuencia calculada f = 1/T (Hz) | Diferencia respecto al valor nominal (Hz) |
|---|---:|---:|---:|---:|
| Tono 500 Hz | 500 | 0.00242 | 413 | 87 |
| Tono 1000 Hz | 1000 | 0.00105 | 952 | 48 |
| Tono 2000 Hz | 2000 | 0.0005 | 2000 | 0 |
| Tono 4000 Hz | 4000 | 0.00024 | 4166.6 | 166.6 |

*Tabla F.1. Comparación entre frecuencia configurada y frecuencia calculada a partir del periodo medido.*

Los valores obtenidos corresponden a una estimación mediante lectura manual de la señal observada en el osciloscopio. Las diferencias encontradas se ubicaron entre 0 Hz y 166.6 Hz respecto a los valores nominales.

Estas variaciones pueden estar relacionadas con:

- Resolución de lectura del osciloscopio.
- Identificación manual de los picos de la señal.
- Escala temporal utilizada durante la medición.
- Condiciones del entorno de captura.
- Características del sistema de reproducción y captura.

Los resultados permiten verificar que el módulo genera señales cercanas a las frecuencias configuradas y que los estímulos utilizados durante los ensayos presentan consistencia dentro del contexto experimental.

---

## F.5.2 Nivel relativo de salida

El dispositivo utiliza un parámetro interno de nivel relativo de salida para configurar la amplitud de reproducción del estímulo.

Este parámetro corresponde a una configuración interna del sistema y no representa un nivel de presión sonora calibrado.

| Estímulo | Nivel relativo configurado |
|---|---|
| Tonos sintéticos | 0.3 / 0.5 / 0.7 |
| Sonidos naturales | 0.5 |

La intensidad relativa fue utilizada como parámetro reproducible entre ensayos experimentales, sin interpretarse como una medición audiológica del nivel sonoro recibido por el participante.

---

# F.6 Evidencia fotográfica

![Captura del osciloscopio para 500 Hz](./images/anexo_f_osciloscopio_500hz.jpeg)

*Figura F.2. Señal capturada para el estímulo tonal de 500 Hz.*

![Captura del osciloscopio para 1000 Hz](./images/anexo_f_osciloscopio_1000hz.jpeg)

*Figura F.3. Señal capturada para el estímulo tonal de 1000 Hz.*

![Captura del osciloscopio para 2000 Hz](./images/anexo_f_osciloscopio_2000hz.jpeg)

*Figura F.4. Señal capturada para el estímulo tonal de 2000 Hz.*

![Captura del osciloscopio para 4000 Hz](./images/anexo_f_osciloscopio_4000hz.jpeg)

*Figura F.5. Señal capturada para el estímulo tonal de 4000 Hz.*

---

# F.7 Conclusiones de la validación técnica

La evaluación realizada permitió verificar el funcionamiento del módulo de estímulo sonoro mediante la comparación entre las frecuencias configuradas y las frecuencias calculadas a partir del periodo medido en el osciloscopio.

Las diferencias observadas se encontraron entre 0 Hz y 166.6 Hz, asociadas principalmente al método de medición manual y a la resolución de lectura de la señal.

Los resultados evidencian que el dispositivo reproduce estímulos consistentes con los parámetros definidos para el protocolo experimental, permitiendo su utilización dentro del alcance establecido como herramienta experimental de apoyo.

---

# F.8 Evaluación de pertinencia de la información generada por el dispositivo

Esta sección documenta la valoración realizada por profesionales en salud auditiva sobre la utilidad, claridad y pertinencia de la información presentada por la plataforma web desarrollada.

La evaluación estuvo orientada a determinar si los datos generados por el dispositivo pueden servir como apoyo para la revisión de los ensayos experimentales.

## F.8.1 Instrumento aplicado a especialistas

[Agregar aquí el cuestionario aplicado a las cinco fonoaudiólogas]

## F.8.2 Resultados de la valoración

[Agregar aquí resultados estadísticos o análisis cualitativo]

---

# F.9 Concordancia entre clasificación del dispositivo y valoración especialista

Durante la validación experimental, la especialista en salud auditiva tuvo acceso a los registros audiovisuales y a la información presentada mediante la plataforma web.

La valoración realizada por la especialista fue comparada con la clasificación operacional generada por el modelo computacional.

| Ensayo | Clasificación del dispositivo | Valoración especialista | Concordancia |
|---|---|---|---|
| [Completar] | | | |

Los resultados de concordancia permitieron analizar el nivel de correspondencia entre la salida computacional y la interpretación profesional de los registros.

---

# F.10 Evaluación de usabilidad mediante System Usability Scale (SUS)

La escala SUS fue aplicada a padres de familia y a la especialista vinculada al proyecto con el propósito de evaluar la facilidad de uso percibida, claridad de interacción y aceptación de la plataforma desarrollada.

## Instrumento SUS

[Agregar cuestionario SUS aplicado]

## Resultados SUS

[Agregar resultados obtenidos]

---

# F.11 Evaluación de aceptación tecnológica mediante TAM

La aceptación tecnológica del sistema fue evaluada mediante un cuestionario basado en el Technology Acceptance Model (TAM).

Las dimensiones consideradas fueron:

- Utilidad percibida.
- Facilidad de uso percibida.
- Intención de uso.

El instrumento fue aplicado a padres de familia y a la especialista vinculada al proyecto.

## Instrumento TAM

[Agregar cuestionario aplicado]

## Resultados TAM

[Agregar resultados obtenidos]

---

# F.12 Relación de instrumentos con el objetivo específico 4

| Componente evaluado | Método utilizado | Participantes / evidencia |
|---|---|---|
| Desempeño técnico del estímulo sonoro | Medición experimental de frecuencia | Hardware del dispositivo |
| Pertinencia de información | Encuesta de valoración | Cinco fonoaudiólogas |
| Concordancia de clasificación | Comparación dispositivo-especialista | Especialista en salud auditiva |
| Usabilidad | System Usability Scale (SUS) | Padres y especialista |
| Aceptación tecnológica | Technology Acceptance Model (TAM) | Padres y especialista |

Este conjunto de evaluaciones permitió abordar la validación del dispositivo desde una perspectiva técnica, funcional y de interacción con usuarios, manteniendo el alcance experimental definido para el proyecto.

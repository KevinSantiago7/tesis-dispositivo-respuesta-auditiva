# ANEXO F. PRUEBA DE EFECTIVIDAD DEL MÓDULO DE ESTÍMULO SONORO

## F.1 Objetivo de la prueba

Verificar la correspondencia entre los parámetros de frecuencia y nivel relativo de salida configurados internamente en el módulo de estímulo sonoro del dispositivo (ver sección 4.2, Tabla 8) y los valores reales de frecuencia e intensidad acústica reproducidos por los parlantes, con el fin de comprobar la reproducibilidad y consistencia técnica del componente antes de su uso en los ensayos experimentales con los participantes.

Esta prueba corresponde a una **verificación técnica de efectividad del hardware**, no a una calibración audiológica clínica. Sus resultados no deben interpretarse como niveles de presión sonora certificados para uso diagnóstico, sino como evidencia de que el módulo reproduce de forma consistente los estímulos definidos en el protocolo experimental (ver sección 3.10.1).

## F.2 Instrumentación utilizada

| Instrumento | Marca / Modelo | Función en la prueba |
| --- | --- | --- |
| Micrófono | [completar: marca y modelo] | Captación de la señal acústica emitida por el parlante y conversión a señal eléctrica |
| Osciloscopio | Tektronix 2225 50MHz | Visualización de la forma de onda captada y medición del periodo (T) entre picos consecutivos, a partir del cual se calculó la frecuencia real reproducida |
| [Sonómetro / medidor de nivel de presión sonora, si se usó] | Decibel meter (App), Sonómetro (App) | Medición del nivel de presión sonora (dB) generado por cada estímulo |
| Parlante del dispositivo | [referencia interna del componente, ver sección 4.1] | Elemento evaluado |

> Nota. Si alguno de los instrumentos cuenta con certificado de calibración vigente, adjuntar el documento o su referencia en la sección F.6.

## F.3 Montaje experimental

La medición se realizó bajo las siguientes condiciones:

- **Distancia parlante–micrófono:** [completar, ej. 30 cm], replicando la distancia aproximada entre el dispositivo y el bebé durante los ensayos reales (ver sección 3.7.1).
- **Entorno de medición:** [completar: ambiente controlado / condiciones de silencio relativo / laboratorio X].
- **Posición del parlante:** [completar: orientación frontal, canal evaluado — izquierdo/derecho].
- **Condiciones ambientales:** [completar: nivel de ruido de fondo registrado antes de la prueba, si se midió].

![Montaje experimental de la prueba de efectividad](./images/anexo_f_montaje.png)

*Figura F.1. Disposición del micrófono y el osciloscopio respecto al parlante del dispositivo durante la prueba de efectividad.*

## F.4 Procedimiento de medición

Para verificar si la frecuencia realmente reproducida por el dispositivo correspondía a la frecuencia configurada internamente, se utilizó un micrófono conectado a un osciloscopio: el micrófono captó la señal acústica emitida por el parlante y la convirtió en una señal eléctrica, que el osciloscopio representó gráficamente como una onda en función del tiempo.

El procedimiento seguido para cada estímulo tonal fue el siguiente:

1. Se configuró el dispositivo para reproducir el estímulo a la frecuencia nominal correspondiente (500 Hz, 1000 Hz, 2000 Hz o 4000 Hz, según la Tabla 8, sección 4.2).
2. Se ubicó el micrófono a una distancia fija de [completar] cm del parlante, replicando la disposición usada durante los ensayos con los bebés.
3. Se capturó en el osciloscopio la forma de onda resultante, identificando dos picos consecutivos (pico a pico) sobre la traza.
4. Se midió, a partir de la escala de tiempo del osciloscopio (segundos/división), el intervalo temporal entre esos dos picos consecutivos; este intervalo corresponde al **periodo (T)** de la onda.
5. Se calculó la frecuencia real reproducida aplicando la relación entre periodo y frecuencia:

   **f = 1 / T**

   donde *f* es la frecuencia en Hz y *T* es el periodo medido en segundos.
6. La frecuencia calculada se comparó contra la frecuencia nominal configurada en el dispositivo, para determinar el grado de coincidencia.

Cada medición fue documentada fotográficamente directamente desde la pantalla del osciloscopio (ver Figuras F.2 a F.5).

> Nota metodológica. Este procedimiento de lectura de periodo pico a pico aplica de forma directa a los tonos sintéticos (500, 1000, 2000 y 4000 Hz), por tratarse de señales periódicas con una única frecuencia dominante. Los sonidos pregrabados (campana, sonajero, oveja, vaca) [completar: indicar si se evaluaron únicamente en nivel de intensidad, o si se les aplicó un análisis de frecuencia dominante distinto, dado que son señales complejas no estrictamente periódicas].

## F.5 Resultados

### F.5.1 Frecuencia

Para cada uno de los cuatro tonos evaluados se identificó el periodo (T) entre dos picos consecutivos de la onda capturada en el osciloscopio, y se calculó la frecuencia real mediante f = 1/T. Los resultados se resumen en la Tabla F.1.

| Estímulo | Frecuencia nominal (Hz) | Periodo medido T (s) | Frecuencia calculada f = 1/T (Hz) | Diferencia respecto a lo nominal | Observación |
| --- | --- | --- | --- | --- | --- |
| Tono 500 Hz | 500 | [completar] | [completar] | [completar] | [coincide / diferencia mínima] |
| Tono 1000 Hz | 1000 | [completar] | [completar] | [completar] | [coincide / diferencia mínima] |
| Tono 2000 Hz | 2000 | [completar] | [completar] | [completar] | [coincide / diferencia mínima] |
| Tono 4000 Hz | 4000 | [completar] | [completar] | [completar] | [coincide / diferencia mínima] |

*Tabla F.1. Comparación entre frecuencia nominal configurada, periodo medido en el osciloscopio y frecuencia real calculada por estímulo.*

En términos generales, las frecuencias calculadas a partir del periodo medido en el osciloscopio [coincidieron / se aproximaron] a los valores nominales configurados en el dispositivo, con diferencias mínimas en algunos de los estímulos evaluados. Estas pequeñas diferencias son esperables en este tipo de medición manual y pueden explicarse por factores como:

- **Resolución de lectura del osciloscopio:** el periodo se estima visualmente a partir de las divisiones de la pantalla, lo que introduce un margen de error asociado a la precisión con la que se ubican los picos de la onda.
- **Respuesta en frecuencia del micrófono:** el micrófono utilizado no necesariamente tiene una respuesta perfectamente plana en todo el rango de frecuencias evaluado, lo que puede afectar levemente la forma de la onda capturada.
- **Ruido ambiental y reflexiones acústicas:** al no realizarse la prueba en una cámara anecoica, pequeñas reflexiones del sonido en el entorno pueden distorsionar ligeramente la señal captada.
- **Estabilidad de la fuente de reproducción:** posibles variaciones mínimas en la frecuencia realmente generada por el hardware del dispositivo (parlante y circuito de audio) frente al valor teórico programado.

Estas diferencias, al ser [pequeñas / del orden de X Hz], no comprometen la validez del estímulo como parámetro reproducible del dispositivo: confirman que las frecuencias configuradas se reproducen de forma consistente y cercana al valor nominal, aunque no bajo condiciones de calibración acústica de laboratorio certificado.

### F.5.2 Intensidad (nivel de presión sonora)

| Estímulo | Nivel relativo configurado | Nivel de presión sonora medido — promedio (dB) | Desviación estándar (dB) |
| --- | --- | --- | --- |
| Tono 500 Hz | 0.3 | [completar] | [completar] |
| Tono 500 Hz | 0.5 | [completar] | [completar] |
| Tono 1000 Hz | 0.5 | [completar] | [completar] |
| Tono 2000 Hz | 0.5 | [completar] | [completar] |
| Tono 4000 Hz | 0.7 | [completar] | [completar] |
| Campana | 0.5 | [completar] | [completar] |
| Sonajero | 0.5 | [completar] | [completar] |
| Oveja | 0.5 | [completar] | [completar] |
| Vaca | 0.5 | [completar] | [completar] |

*Tabla F.2. Relación entre el nivel relativo configurado internamente y el nivel de presión sonora real medido.*

> Nota. Completar esta sección si se realizó medición de intensidad con un instrumento adicional (sonómetro). Si la prueba de efectividad se limitó a la verificación de frecuencia mediante osciloscopio, indicarlo aquí y ajustar el objetivo (F.1) y las conclusiones (F.7) en consecuencia.

![Captura del osciloscopio para el estímulo de 1000 Hz](./images/anexo_f_osciloscopio_1000hz.png)

*Figura F.2. Captura del osciloscopio para el estímulo de 1000 Hz, mostrando los picos consecutivos utilizados para calcular el periodo T.*

![Captura del osciloscopio para el estímulo de 4000 Hz](./images/anexo_f_osciloscopio_4000hz.png)

*Figura F.3. Captura del osciloscopio para el estímulo de 4000 Hz, mostrando los picos consecutivos utilizados para calcular el periodo T.*

![Captura del osciloscopio para el estímulo de 500 Hz](./images/anexo_f_osciloscopio_500hz.png)

*Figura F.4. Captura del osciloscopio para el estímulo de 500 Hz, mostrando los picos consecutivos utilizados para calcular el periodo T.*

![Captura del osciloscopio para el estímulo de 2000 Hz](./images/anexo_f_osciloscopio_2000hz.png)

*Figura F.5. Captura del osciloscopio para el estímulo de 2000 Hz, mostrando los picos consecutivos utilizados para calcular el periodo T.*

## F.6 Evidencia fotográfica adicional

![Instrumento de medición utilizado](./images/anexo_f_instrumento.png)

*Figura F.6. Montaje del micrófono y el osciloscopio utilizado para la medición ([marca/modelo]).*

![Vista general del dispositivo durante la prueba](./images/anexo_f_dispositivo_prueba.png)

*Figura F.7. Vista general del dispositivo tecnológico durante la ejecución de la prueba de efectividad del módulo de estímulo sonoro.*

> Nota. Reemplazar las rutas `./images/...` por las imágenes reales del laboratorio, subidas a la carpeta `Anexos/Anexo_F/images/` del repositorio, manteniendo la misma convención de nombres usada en `Anexo_A/Prisma.md`.

## F.7 Conclusiones de la prueba

[Completar con 2-4 líneas de interpretación general, por ejemplo:]

- Las frecuencias calculadas a partir del periodo medido con el osciloscopio se mantuvieron dentro de un margen de ±[completar] Hz respecto a los valores nominales configurados en el dispositivo, lo que indica una reproducción consistente de los estímulos tonales.
- [Si aplica] El nivel de presión sonora mostró una relación [creciente / proporcional / no lineal] respecto al nivel relativo configurado internamente, confirmando que este parámetro puede utilizarse como un indicador reproducible de intensidad relativa, sin constituir una medida audiológica calibrada (ver nota de la Tabla 8, sección 4.2).
- Estos resultados respaldan la validez técnica de las configuraciones utilizadas durante los ensayos experimentales reportados en el Capítulo 5, en el marco de la evaluación de desempeño técnico descrita en la sección 3.10.1.

---

*Este anexo complementa la sección 3.10.1 "Evaluación del desempeño técnico, computacional y validación experimental del dispositivo" y la Tabla 8 (sección 4.2) del documento principal de la monografía.*

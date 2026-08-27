# ANEXO B. PROTOCOLO EXPERIMENTAL DETALLADO

---

## B.1 Objetivo del protocolo

Este anexo presenta la versión ampliada y operativa del protocolo experimental descrito en la sección 3.6.3, con el nivel de detalle necesario para que el procedimiento pueda replicarse de manera consistente entre sesiones, participantes y ensayos. Complementa la Tabla 7 (condiciones operativas del protocolo experimental) y la Figura 4 (montaje experimental de adquisición) presentadas en el cuerpo del documento.

El protocolo aquí descrito no constituye una prueba audiológica ni un procedimiento clínico. Corresponde a la secuencia operativa seguida para presentar estímulos sonoros controlados y registrar audiovisualmente el comportamiento del bebé dentro del alcance experimental del dispositivo.

---

## B.2 Equipo y materiales utilizados

| Componente | Descripción | Función en el protocolo |
|---|---|---|
| Jetson Nano (2 GB) | Unidad de procesamiento local | Ejecuta la interfaz de control, el procesamiento de video, la extracción de características y la clasificación preliminar. |
| Teléfono móvil | Fuente de adquisición audiovisual (modo cámara IP / cámara frontal) | Captura el video del comportamiento del bebé durante la calibración y cada ensayo. |
| Parlante / bocina externa | Dispositivo de reproducción sonora conectado al sistema | Reproduce el estímulo sonoro (tono o sonido pregrabado) configurado para el ensayo. |
| Estructura impresa en 3D | Alojamiento físico de la Jetson Nano, el teléfono móvil y la batería | Integra los componentes en una configuración transportable y reduce la dispersión física del montaje. |
| Batería / unidad de alimentación | Fuente de energía portátil | Alimenta la Jetson Nano y demás componentes durante la sesión. |
| Formatos de consentimiento informado y autorización audiovisual (Anexo D) | Documento físico o digital | Se diligencia y firma antes de iniciar cualquier procedimiento con el bebé. |
| Formato de caracterización del participante | Registro de código del bebé, sexo biológico, edad cronológica y número de sesión (Tabla 6) | Se completa antes de iniciar los ensayos. |

**Nota:** la distancia entre la cámara/parlante y el bebé no se controló ni se registró de forma fija; varió según las condiciones de cada sesión (espacio disponible, posición del cuidador, comodidad del bebé). Esta variabilidad se declara como parte de las condiciones reales de adquisición y no debe interpretarse como un parámetro estandarizado.

---

## B.3 Roles y responsabilidades del equipo

Una sesión típica involucró hasta cinco personas:

| Rol | Persona(s) | Responsabilidad principal |
|---|---|---|
| Bebé | Participante | Sujeto de observación. Su bienestar y comodidad son prioritarios sobre la obtención de datos. |
| Cuidador (padre, madre o acudiente) | — | Firma el consentimiento informado, acompaña y sostiene o supervisa físicamente al bebé durante la sesión, y puede solicitar la interrupción del procedimiento en cualquier momento. |
| Operador principal | Kevin Santiago Oliveros Realpe o Manuela Gaviria Vélez | Configura la sesión y los parámetros del ensayo, verifica las condiciones técnicas (cámara, iluminación, encuadre), ejecuta la calibración, inicia y detiene cada ensayo, y decide la interrupción de la sesión ante señales de malestar del bebé. |
| Apoyo / observación | El investigador que no actúa como operador principal en ese ensayo | Apoya la verificación de condiciones, documenta observaciones adicionales y colabora en el manejo de pausas o imprevistos. |
| Especialista en salud auditiva | Marcela Jaramillo | Participa en calidad de supervisión y valoración del procedimiento, aportando criterio profesional sobre la pertinencia y el desarrollo de la sesión dentro del alcance experimental del estudio. |

La asignación específica de "operador principal" y "apoyo" entre Kevin y Manuela pudo variar entre sesiones, sin que esto alterara el procedimiento operativo descrito en este anexo.

---

## B.4 Condiciones del entorno

Antes de iniciar cualquier ensayo se verificaron las siguientes condiciones, consistentes con lo descrito en 3.6 y 3.6.4:

- Iluminación suficiente para permitir la detección y el seguimiento facial.
- Reducción razonable del ruido ambiental del espacio utilizado.
- Espacio que permitiera ubicar al bebé en una posición cómoda, segura y con el rostro visible hacia la cámara.
- Estabilidad de la cámara (fija o sostenida sin movimiento durante el registro).
- Visibilidad del rostro y la parte superior del cuerpo del bebé dentro del encuadre.

Estas condiciones se evaluaron de forma cualitativa por el operador antes de cada sesión; no se emplearon instrumentos de medición de iluminación (lux) ni de nivel de ruido ambiental (dB), por lo que su verificación dependió del criterio del equipo investigador en el momento de la adquisición.

---

## B.5 Configuración previa a la sesión

1. El cuidador recibe la información del estudio y firma el consentimiento informado y la autorización para registro audiovisual (Anexo D).
2. El operador registra o selecciona al bebé mediante su código de identificación en la interfaz del dispositivo.
3. El operador crea la sesión correspondiente y diligencia la información de caracterización autorizada (sexo biológico, edad cronológica) según la Tabla 6.
4. El equipo dispone físicamente el montaje: la estructura con la Jetson Nano, el teléfono móvil y la batería; el parlante externo conectado; y el espacio donde se ubicará al bebé y al cuidador.
5. El operador verifica la conexión de la cámara (transmisión IP desde el teléfono), el encuadre, la iluminación y la visibilidad del rostro del bebé.
6. Se ajusta la posición del bebé, la cámara o el parlante si alguna condición inicial no es adecuada.

---

## B.6 Procedimiento paso a paso por ensayo

1. **Calibración inicial.** Con el bebé en posición estable y el rostro visible, el operador ejecuta la calibración para establecer la referencia de orientación cefálica (línea base) y las condiciones faciales previas al estímulo.
2. **Configuración del ensayo.** El operador define, desde la interfaz del dispositivo, el tipo de estímulo (tono sintético o sonido pregrabado), la frecuencia (cuando aplica), la duración, la lateralidad (izquierda o derecha) y el nivel relativo de salida.
3. **Inicio del registro audiovisual.** Se inicia la grabación. Los primeros **3 segundos** constituyen la fase basal (sin estímulo).
4. **Presentación del estímulo.** El parlante externo reproduce el estímulo configurado durante **1 segundo**.
5. **Observación posterior.** El registro audiovisual continúa hasta completar una duración total de **8 segundos** por ensayo (es decir, 4 segundos adicionales de observación tras el estímulo).
6. **Finalización y almacenamiento.** Al completarse el ensayo, el sistema asocia el archivo audiovisual con los identificadores del bebé, la sesión y el ensayo, junto con los parámetros del estímulo utilizado.
7. **Verificación breve.** El operador o el apoyo revisan de forma general que el ensayo se haya registrado correctamente antes de continuar con el siguiente.
8. **Repetición o pausa.** El procedimiento se repite según el estado de alerta y la disposición del bebé. Se incorporan pausas cuando es necesario (ver B.7).

Este ciclo corresponde a la unidad mínima de registro (ensayo) descrita en 3.6 y se repite tantas veces como lo permita el estado conductual del bebé, sin una cantidad fija predefinida de ensayos por sesión.

---

## B.7 Parámetros de estimulación utilizados

| Parámetro | Valores utilizados | Observación |
|---|---|---|
| Tipo de estímulo | Tono sintético / sonido pregrabado | Definido según el diseño del ensayo. |
| Frecuencia (tonos) | 500, 1000, 2000 y 4000 Hz | No aplica a sonidos pregrabados. |
| Nivel relativo de salida | 0,3; 0,5 y 0,7 | Parámetro interno del reproductor; **no corresponde a un valor calibrado en dB SPL** ni a un umbral auditivo clínico. |
| Lateralidad | Izquierda / derecha | Definida antes de cada ensayo. |
| Duración total del ensayo | 8 segundos | Fase basal (3 s) + estímulo (1 s) + observación posterior (4 s). |
| Duración del estímulo | 1 segundo | Constante para todos los ensayos. |

---

## B.8 Estructura temporal del ensayo

```
Segundo 0                Segundo 3        Segundo 4                  Segundo 8
     |------ Línea base ------|-- Estímulo --|------ Observación posterior ------|
     |        (3 s)           |    (1 s)     |              (4 s)                |
```

Esta estructura temporal es constante para todos los ensayos, independientemente del tipo de estímulo, la frecuencia, la lateralidad o el nivel relativo configurado.

---

## B.9 Criterios de interrupción y pausa

De acuerdo con lo establecido en 3.6 y 3.6.1, el procedimiento se interrumpía de forma inmediata ante cualquiera de las siguientes situaciones:

- Llanto persistente del bebé.
- Signos de fatiga o pérdida de atención sostenida.
- Incomodidad manifiesta del bebé.
- Solicitud expresa del cuidador para pausar o finalizar la sesión.

En estos casos, el bienestar del participante tuvo prioridad sobre la obtención de una cantidad determinada de ensayos. La sesión podía retomarse posteriormente si las condiciones lo permitían, o darse por finalizada de forma definitiva.

---

## B.10 Criterios de aceptación y descarte de ensayos

Los criterios detallados de aceptación y descarte de cada ensayo (visibilidad facial, iluminación, estabilidad de la cámara, continuidad del video, calidad de la línea base, entre otros) se describen en la sección **3.6.6** del cuerpo del documento y no se repiten en este anexo para evitar duplicidad. Este protocolo (Anexo B) describe **cómo se ejecutó** la adquisición; la sección 3.6.6 describe **qué ensayos se conservaron** para el análisis y el entrenamiento del modelo.

---

## B.11 Registro y trazabilidad

Cada ensayo ejecutado bajo este protocolo queda asociado, de forma automática por el dispositivo, a:

- El código del bebé (`baby_id`).
- El número de sesión (`session_id`).
- El identificador del ensayo (`trial_id`).
- Los parámetros del estímulo configurado (tipo, frecuencia, duración, lateralidad, nivel relativo).
- El archivo audiovisual correspondiente.

Esta trazabilidad permite reconstruir, para cualquier ensayo, las condiciones exactas bajo las cuales fue adquirido, en correspondencia con lo descrito en la sección 3.6.5 y el Anexo E (diccionario del dataset).

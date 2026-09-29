# ANEXO B. PROTOCOLO EXPERIMENTAL DETALLADO

---

# B.1 Objetivo del protocolo

Este anexo presenta la versión ampliada y operativa del protocolo experimental descrito en la sección **3.7 Protocolo experimental y adquisición de datos**, con el nivel de detalle necesario para comprender la ejecución de las sesiones, la adquisición de registros audiovisuales y la organización de la información obtenida durante los ensayos.

El protocolo complementa la descripción metodológica presentada en el capítulo 3 y las figuras asociadas al montaje experimental, permitiendo documentar la secuencia seguida desde la preparación del participante hasta la generación de los registros utilizados durante las etapas posteriores de procesamiento.

El procedimiento descrito no corresponde a una prueba audiológica ni a un procedimiento clínico de evaluación auditiva. Corresponde a una estrategia experimental orientada a presentar estímulos sonoros controlados y registrar mediante medios audiovisuales manifestaciones conductuales observables en bebés de 0 a 6 meses, dentro del alcance tecnológico del dispositivo desarrollado.

El protocolo fue construido considerando las condiciones reales de adquisición con población infantil, por lo que incluye las decisiones metodológicas adoptadas durante la ejecución, los ajustes realizados ante limitaciones técnicas y las condiciones consideradas para conservar la calidad y trazabilidad de los registros obtenidos.

---

# B.2 Equipo y materiales utilizados

Para la ejecución del protocolo experimental se emplearon los componentes físicos y documentos necesarios para realizar la adquisición audiovisual, generación de estímulos y organización de la información obtenida.

| Componente | Descripción | Función dentro del protocolo |
|---|---|---|
| Jetson Nano (2 GB) | Unidad de procesamiento embebida utilizada como núcleo computacional del sistema. | Ejecuta procesos asociados con la interfaz de control, procesamiento audiovisual, extracción de características y comunicación con los módulos desarrollados. |
| Teléfono móvil | Dispositivo utilizado como sistema de adquisición audiovisual mediante cámara IP. | Permite capturar el comportamiento observable del bebé durante la calibración y los ensayos experimentales. |
| Parlante externo | Dispositivo encargado de reproducir los estímulos sonoros configurados desde el sistema. | Genera los estímulos utilizados durante cada ensayo experimental. |
| Estructura impresa en 3D | Soporte físico diseñado para integrar los componentes principales del prototipo. | Permite organizar físicamente la unidad de procesamiento, cámara y elementos de alimentación. |
| Batería / fuente de alimentación portátil | Sistema de suministro energético para los componentes electrónicos. | Permite la operación del dispositivo durante las sesiones experimentales. |
| Consentimiento informado y autorización audiovisual (Anexo D) | Documento de autorización firmado por el representante legal del participante. | Permite formalizar la participación voluntaria y el manejo de registros audiovisuales. |
| Formato de caracterización del participante | Registro de variables descriptivas autorizadas. | Permite organizar la información del participante mediante identificadores internos. |

**Nota:** Debido al carácter experimental del dispositivo y a las condiciones reales de adquisición, variables ambientales como distancia exacta entre participante, cámara y fuente sonora fueron consideradas condiciones contextuales del ensayo y no parámetros experimentales controlados.

---

# B.3 Roles y responsabilidades durante la sesión experimental

Una sesión experimental involucró diferentes actores con responsabilidades específicas dentro del procedimiento.

| Rol | Responsabilidad |
|---|---|
| Bebé participante | Participante del estudio cuya respuesta conductual observable fue registrada mediante adquisición audiovisual. Su bienestar y comodidad fueron considerados prioritarios durante todo el procedimiento. |
| Padre, madre o cuidador | Responsable de autorizar la participación mediante consentimiento informado, acompañar al bebé durante la sesión y solicitar pausas o finalización del procedimiento cuando lo considerara necesario. |
| Operador del dispositivo | Encargado de configurar la sesión, verificar las condiciones técnicas del sistema, ejecutar la calibración inicial, iniciar y finalizar los ensayos y supervisar el correcto funcionamiento del prototipo. |
| Apoyo experimental | Responsable de colaborar en la preparación del espacio, observación de condiciones del ensayo y registro de situaciones particulares durante la adquisición. |
| Profesional relacionado con el área auditiva | Participante dentro del proceso de revisión experimental, aportando observaciones relacionadas con la pertinencia del procedimiento y la interpretación de la información generada dentro del alcance definido. |

La asignación de funciones entre los investigadores responsables pudo variar entre sesiones sin modificar la estructura metodológica establecida.

---

# B.4 Condiciones del entorno experimental

Antes de iniciar cada sesión se verificaron las condiciones necesarias para garantizar una adquisición adecuada del registro audiovisual.

Las condiciones consideradas fueron:

- Iluminación suficiente para permitir la detección y seguimiento facial.
- Reducción razonable del ruido ambiental presente en el lugar de adquisición.
- Espacio adecuado para ubicar al bebé en una posición segura y cómoda.
- Estabilidad de la cámara durante el registro.
- Visibilidad suficiente del rostro y parte superior del cuerpo del bebé dentro del encuadre.

Estas condiciones fueron verificadas cualitativamente por el equipo investigador antes de iniciar cada sesión.

No se utilizaron instrumentos externos para medir variables ambientales como nivel de iluminación en lux o nivel de presión sonora en decibeles, por lo que dichas condiciones fueron consideradas según la observación del equipo durante la ejecución experimental.

---

# B.5 Configuración previa a la sesión

Antes de iniciar los ensayos experimentales se realizaron las siguientes actividades:

1. **Información y autorización del participante**

   El padre, madre o representante legal recibió información relacionada con el objetivo del estudio, el procedimiento experimental, los registros obtenidos y el alcance tecnológico del dispositivo.

   Posteriormente se realizó la firma del consentimiento informado y autorización para adquisición audiovisual.

2. **Identificación del participante**

   El operador registró al participante mediante un código interno asignado para mantener la trazabilidad de la información sin utilizar directamente datos personales dentro del procesamiento computacional.

3. **Creación de la sesión experimental**

   Se creó la sesión correspondiente dentro de la plataforma desarrollada, asociando la información básica autorizada del participante.

4. **Preparación del montaje tecnológico**

   Se realizó la disposición física del dispositivo:

   - Unidad de procesamiento.
   - Cámara audiovisual.
   - Sistema de reproducción sonora.
   - Fuente de alimentación.

5. **Verificación del sistema**

   Antes de iniciar el ensayo se comprobó:

   - Conexión de la cámara.
   - Comunicación entre componentes.
   - Encuadre del participante.
   - Visibilidad facial.
   - Condiciones generales de iluminación.

6. **Ajustes iniciales**

   Cuando alguna condición no era adecuada, se realizaron modificaciones relacionadas con:

   - Posición del bebé.
   - Ubicación de la cámara.
   - Organización del espacio experimental.
   - Posición del sistema de reproducción sonora.

---

# B.6 Procedimiento paso a paso por ensayo

Cada ensayo fue considerado como la unidad mínima de adquisición experimental, debido a que relacionó un estímulo específico con un registro audiovisual y los datos generados durante el procesamiento posterior.

El procedimiento seguido fue el siguiente:

## 1. Calibración inicial

Con el bebé ubicado frente al dispositivo y manteniendo la visibilidad del rostro, se realizó una calibración inicial para establecer una referencia del estado previo al estímulo.

Esta etapa permitió obtener una línea base relacionada con:

- Orientación cefálica inicial.
- Condiciones geométricas del rostro.
- Referencias utilizadas durante el análisis posterior.

---

## 2. Configuración del ensayo

Antes de iniciar cada ensayo se configuraron los parámetros correspondientes al estímulo:

- Tipo de estímulo.
- Frecuencia cuando aplicaba.
- Duración.
- Lateralidad.
- Nivel relativo de salida.

Estos parámetros fueron almacenados junto con la información del ensayo para conservar la trazabilidad experimental.

---

## 3. Inicio del registro audiovisual

Una vez configuradas las condiciones del ensayo, se inició la captura audiovisual del comportamiento observable del bebé.

El registro permitió conservar información relacionada con:

- Cambios de orientación cefálica.
- Variaciones faciales.
- Cambios temporales del comportamiento observado.

---

## 4. Presentación del estímulo sonoro

Durante el registro audiovisual se reprodujo el estímulo configurado previamente.

La relación temporal entre la presentación del estímulo y los cambios observados posteriormente fue utilizada como criterio experimental de análisis.

---

## 5. Observación posterior

Después de la presentación del estímulo se mantuvo la adquisición audiovisual durante el periodo establecido para observar modificaciones conductuales posteriores.

Los registros obtenidos no fueron interpretados directamente durante la sesión como una respuesta clínica, sino conservados para procesamiento y análisis posterior.

---

## 6. Finalización y almacenamiento

Al finalizar cada ensayo, la información generada fue asociada con:

- Código del participante.
- Sesión experimental.
- Identificador del ensayo.
- Parámetros del estímulo.
- Registro audiovisual obtenido.

Esta estructura permitió mantener la relación entre las condiciones iniciales del ensayo y los resultados generados durante las etapas computacionales posteriores.

---

---

# B.7 Parámetros de estimulación utilizados

Los parámetros de estimulación fueron definidos previamente para cada ensayo experimental y registrados junto con la información correspondiente al participante, sesión y prueba realizada.

La configuración de estos parámetros permitió establecer las condiciones bajo las cuales fue presentado cada estímulo sonoro y conservar la trazabilidad entre la señal generada y el registro audiovisual obtenido.

| Parámetro | Valores utilizados | Observación |
|---|---|---|
| Tipo de estímulo | Tono sintético / sonido pregrabado | Seleccionado según la configuración establecida para cada ensayo. |
| Frecuencia | 500 Hz, 1000 Hz, 2000 Hz y 4000 Hz | Aplicable para estímulos tonales generados por el sistema. |
| Nivel relativo de salida | 0,3; 0,5 y 0,7 | Parámetro interno del sistema de reproducción. No corresponde a una medición calibrada en dB SPL ni representa un umbral auditivo clínico. |
| Lateralidad | Izquierda / derecha | Define la ubicación relativa de presentación del estímulo durante el ensayo. |
| Duración del estímulo | 1 segundo | Tiempo utilizado para la presentación del estímulo sonoro. |

Los parámetros configurados fueron almacenados junto con cada ensayo, permitiendo reconstruir las condiciones experimentales utilizadas durante la adquisición.

---

# B.8 Estructura temporal del ensayo

Cada ensayo experimental fue organizado mediante una estructura temporal orientada a diferenciar el comportamiento inicial del bebé y las modificaciones observadas posteriormente a la presentación del estímulo sonoro.

La organización temporal utilizada como referencia fue:

Segundo 0                 Segundo 3          Segundo 4                  Segundo 8
|------ Línea base --------|--- Estímulo ----|------ Observación posterior ------|
 3 segundos              1 segundo              4 segundos
La estructura del ensayo estuvo conformada por tres etapas:

## Línea base

Corresponde al periodo previo a la presentación del estímulo.

Durante esta etapa se estableció una referencia inicial del estado del participante, considerando condiciones relacionadas con orientación cefálica, posición facial y comportamiento observable previo.

## Presentación del estímulo

Corresponde al intervalo durante el cual el sistema reprodujo el estímulo sonoro configurado previamente.

Durante este periodo se mantuvo la adquisición audiovisual para conservar la relación temporal entre la presentación del estímulo y el comportamiento registrado.

## Observación posterior

Corresponde al periodo posterior al estímulo durante el cual se continuó registrando el comportamiento observable del bebé.

Esta etapa permitió analizar cambios respecto a la condición inicial, considerando que las manifestaciones conductuales infantiles pueden presentar variabilidad natural entre participantes y ensayos.

La estructura temporal permitió organizar los registros audiovisuales y facilitar las etapas posteriores de extracción de características y clasificación computacional.

---

# B.9 Criterios de interrupción y pausa durante la sesión

Debido a que la población participante correspondió a bebés entre 0 y 6 meses, el protocolo experimental incorporó criterios orientados a proteger el bienestar del participante durante la ejecución de las pruebas.

La sesión podía ser pausada o finalizada ante la presencia de alguna de las siguientes situaciones:

- Llanto persistente del bebé.
- Signos de fatiga o incomodidad.
- Pérdida prolongada del estado de atención.
- Condiciones que dificultaran mantener una adquisición adecuada.
- Solicitud del padre, madre o cuidador responsable.

Cuando se presentaba alguna de estas condiciones, se priorizaba el bienestar del bebé sobre la continuidad del registro experimental.

En caso de ser posible, la sesión podía retomarse posteriormente después de permitir que el participante recuperara un estado adecuado para continuar.

La cantidad de ensayos realizados dependió de las condiciones particulares de cada sesión, evitando establecer una cantidad fija que pudiera afectar el bienestar del participante.

---

# B.10 Criterios de aceptación y descarte de ensayos

Durante la adquisición experimental se definieron criterios para determinar cuáles registros contaban con condiciones suficientes para ser utilizados durante las etapas posteriores de procesamiento computacional.

Estos criterios permitieron garantizar que cada ensayo conservara la relación entre:

- Configuración del estímulo presentado.
- Registro audiovisual obtenido.
- Variables extraídas durante el procesamiento.
- Clasificación computacional generada.

Los criterios de aceptación considerados fueron:

| Criterio | Descripción |
|---|---|
| Visibilidad facial adecuada | El rostro del bebé debía permanecer visible durante el ensayo para permitir la detección facial y extracción de características. |
| Condiciones adecuadas de iluminación | La escena debía presentar condiciones suficientes para identificar referencias faciales durante el procesamiento. |
| Continuidad del registro audiovisual | El video debía conservar la secuencia completa correspondiente al ensayo experimental. |
| Existencia de línea base | El registro debía contener información previa al estímulo para establecer una referencia inicial. |
| Disponibilidad de características computacionales | El sistema debía permitir obtener las variables definidas para el modelo de clasificación. |
| Correspondencia experimental | Debía mantenerse la relación entre el estímulo configurado, el registro obtenido y la información generada posteriormente. |

Los registros fueron descartados cuando presentaban condiciones que limitaban el análisis computacional, tales como:

- Obstrucción parcial o total del rostro del bebé.
- Pérdida prolongada del seguimiento facial.
- Fallas durante la adquisición audiovisual.
- Ausencia de información asociada al estímulo presentado.
- Condiciones externas que impidieran diferenciar adecuadamente la información registrada.

La aplicación de estos criterios permitió conservar registros con condiciones suficientes para las etapas posteriores de extracción de características, entrenamiento y evaluación del modelo.

---

# B.11 Organización y trazabilidad de los datos

Cada ensayo experimental fue almacenado mediante una estructura jerárquica diseñada para conservar la relación entre la información obtenida durante la adquisición y los resultados generados durante el procesamiento computacional.

La estructura utilizada fue:

| Elemento | Información asociada |
|---|---|
| Participante | Código interno asignado al bebé para mantener anonimización y trazabilidad. |
| Sesión | Información general correspondiente a la jornada experimental. |
| Ensayo | Registro individual asociado a una configuración experimental específica. |
| Estímulo | Tipo, frecuencia, duración, lateralidad y nivel relativo configurado. |
| Registro audiovisual | Video obtenido durante la ejecución del ensayo. |
| Características | Variables extraídas mediante las técnicas de procesamiento computacional. |
| Modelo | Clasificación operacional generada por el algoritmo de aprendizaje automático. |

La trazabilidad completa del sistema fue organizada mediante la siguiente relación:

Participante ↓ Sesión experimental ↓ Ensayo ↓ Configuración del estímulo ↓ Registro audiovisual ↓ Extracción de características ↓ Modelo de clasificación ↓ Resultado operacional



Esta estructura permitió conservar la relación entre la evidencia inicial obtenida durante la sesión experimental y la información generada posteriormente mediante los procesos computacionales.

La descripción detallada de las variables utilizadas durante el procesamiento se presenta en el **Anexo E. Diccionario del dataset**, mientras que los resultados obtenidos mediante el modelo de clasificación se presentan en el **Anexo H. Resultados detallados del modelo Random Forest**.

---


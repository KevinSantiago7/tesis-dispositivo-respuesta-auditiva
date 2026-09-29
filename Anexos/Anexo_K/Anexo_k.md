
# ANEXO K. MARCO TEÓRICO COMPLEMENTARIO Y AMPLIACIÓN DEL ESTADO DEL ARTE

Este anexo presenta la ampliación de los fundamentos conceptuales y tecnológicos relacionados con el desarrollo del dispositivo experimental. La información incluida corresponde a contenidos complementarios que, debido a su nivel de detalle, fueron trasladados desde el Capítulo 2 con el propósito de mantener una estructura más sintetizada del documento principal.

El contenido de este anexo permite profundizar en los fundamentos de evaluación auditiva infantil, análisis de respuestas conductuales, visión por computador, aprendizaje automático y tecnologías digitales aplicadas a sistemas de apoyo en salud.

---

# K.1 MÉTODOS DE EVALUACIÓN AUDITIVA INFANTIL

## K.1.1 Emisiones otoacústicas (OAE)

Las emisiones otoacústicas corresponden a respuestas acústicas generadas principalmente por la actividad de las células ciliadas externas de la cóclea. Estas respuestas pueden registrarse mediante una sonda ubicada en el canal auditivo externo, permitiendo obtener información objetiva relacionada con el funcionamiento del sistema auditivo sin requerir una respuesta activa por parte del bebé [15], [19], [20].

Debido a su carácter no invasivo, rapidez de aplicación y facilidad de adquisición, las emisiones otoacústicas son ampliamente utilizadas dentro de programas de detección auditiva temprana. Su implementación permite identificar bebés que pueden requerir procesos posteriores de evaluación audiológica.

Sin embargo, la calidad de los resultados puede verse afectada por factores asociados a las condiciones de adquisición, incluyendo ruido ambiental, movimiento del bebé, características del oído medio y adecuada colocación de la sonda. Por esta razón, requieren condiciones apropiadas de aplicación e interpretación profesional [19], [20].

Diversos desarrollos tecnológicos han buscado aumentar la disponibilidad de esta metodología mediante sistemas portátiles, integración con dispositivos móviles y componentes comerciales de menor costo. Estas propuestas buscan reducir barreras relacionadas con infraestructura y acceso a equipos especializados [13]–[15].

---

## K.1.2 Potenciales evocados auditivos del tronco encefálico (ABR)

Los potenciales evocados auditivos del tronco encefálico (ABR) permiten registrar la actividad eléctrica generada en diferentes etapas de la vía auditiva como respuesta a estímulos sonoros. Debido a que no dependen de una respuesta voluntaria del bebé, constituyen una herramienta importante dentro de la evaluación auditiva objetiva durante los primeros meses de vida [7], [20], [21].

A diferencia de las emisiones otoacústicas, los ABR requieren generalmente la utilización de electrodos y equipos especializados para adquirir señales eléctricas asociadas con la respuesta auditiva. Además, las condiciones de adquisición deben controlarse para reducir interferencias ocasionadas por movimiento o actividad muscular.

Investigaciones recientes han explorado alternativas orientadas a automatizar el procesamiento de estas señales y desarrollar sistemas más portátiles. Estos avances incorporan herramientas computacionales e inteligencia artificial para apoyar la interpretación de registros electrofisiológicos [2], [5], [9], [12].

---

## K.1.3 Evaluación basada en respuestas conductuales

Además de los métodos fisiológicos, la evaluación auditiva infantil puede considerar respuestas observables posteriores a la presentación de estímulos sonoros. Estas manifestaciones pueden incluir orientación hacia la fuente sonora, cambios en la atención, movimientos cefálicos, modificaciones posturales y expresiones faciales [24], [31], [32].

La audiometría por observación del comportamiento (Behavioral Observation Audiometry, BOA) utiliza este principio mediante la observación de cambios conductuales asociados temporalmente con estímulos auditivos.

Sin embargo, la interpretación de estas respuestas presenta limitaciones debido a la influencia de factores como edad, desarrollo motor, estado de alerta, fatiga, interacción con el cuidador y experiencia del evaluador.

Por esta razón, las respuestas conductuales representan una fuente complementaria de información y requieren condiciones estructuradas de observación y análisis.

---

# K.2 RESPUESTAS CONDUCTUALES INFANTILES FRENTE A ESTÍMULOS SONOROS

## K.2.1 Desarrollo motor y variabilidad conductual

Durante los primeros meses de vida, las respuestas conductuales frente a estímulos externos presentan una alta variabilidad debido al proceso progresivo de maduración motora y perceptiva.

El control cefálico, la estabilidad postural y la capacidad de orientar la cabeza hacia estímulos externos evolucionan progresivamente durante esta etapa. En consecuencia, una respuesta observable puede presentar diferencias entre individuos e incluso entre diferentes ensayos realizados por un mismo bebé [31], [32].

La variabilidad conductual también está influenciada por factores como estado de alerta, fatiga, habituación, movimientos espontáneos e interacción con el cuidador.

---

## K.2.2 Respuesta orientadora frente a estímulos sonoros

La respuesta orientadora corresponde a un conjunto de cambios conductuales que pueden aparecer después de la presentación de un estímulo acústico.

Estas manifestaciones pueden incluir modificaciones en la orientación cefálica, atención visual, actividad motora o postura corporal. Sin embargo, la presencia de una respuesta observable no permite establecer por sí sola una relación directa con la percepción auditiva, debido a la influencia de múltiples factores asociados al comportamiento infantil.

Por esta razón, el análisis de estas respuestas requiere considerar el contexto experimental, las condiciones iniciales del participante y la evolución temporal del comportamiento.

---

# K.3 INTEGRACIÓN DE HERRAMIENTAS COMPUTACIONALES EN ANÁLISIS BIOMÉDICO

Las herramientas computacionales aplicadas a contextos biomédicos permiten transformar información compleja obtenida mediante diferentes fuentes de adquisición en representaciones cuantificables.

La visión por computador y el aprendizaje automático han sido utilizados para analizar imágenes, videos y señales, permitiendo identificar patrones que pueden complementar la interpretación realizada por profesionales especializados [25], [31]–[37], [41]–[46].

Sin embargo, la incorporación de algoritmos computacionales en salud requiere considerar aspectos relacionados con la calidad de los datos, la trazabilidad de la información y la interpretación responsable de los resultados generados.

Un sistema computacional aplicado a información biomédica debe conservar la relación entre:

- Datos adquiridos.
- Variables extraídas.
- Procesamiento realizado.
- Resultados obtenidos.

Esta relación permite mantener el contexto original de la información y facilita procesos posteriores de revisión y análisis.

---

# K.4 VISIÓN POR COMPUTADOR APLICADA AL ANÁLISIS DEL COMPORTAMIENTO INFANTIL

## K.4.1 Detección facial y puntos de referencia geométricos

Los algoritmos de detección facial permiten localizar regiones del rostro y establecer puntos de referencia geométricos (*facial landmarks*) asociados con estructuras como ojos, cejas, nariz y boca.

Estos puntos permiten representar la información facial mediante coordenadas espaciales que pueden analizarse durante una secuencia temporal.

A partir de estas coordenadas pueden calcularse:

- Desplazamientos.
- Velocidades.
- Cambios angulares.
- Variaciones relativas.

Estas representaciones permiten transformar cambios visuales en variables cuantificables para el análisis computacional [48], [49].

---

## K.4.2 Análisis del movimiento cefálico

El movimiento infantil constituye una fuente de información utilizada para caracterizar patrones motores y conductuales mediante técnicas computacionales.

Diferentes investigaciones han empleado estimación de pose, seguimiento temporal y análisis de movimiento para estudiar trayectorias corporales y cambios posturales en población infantil [33]–[37].

Debido a la variabilidad natural del comportamiento infantil, los análisis computacionales suelen considerar características dinámicas y cambios relativos respecto a condiciones iniciales.

---

## K.4.3 Indicadores faciales como información complementaria

Las expresiones faciales pueden aportar información adicional sobre cambios conductuales observables durante una interacción experimental.

Variables relacionadas con movimientos oculares, apertura bucal, posición de cejas y modificaciones de la configuración facial pueden ser analizadas mediante técnicas de procesamiento visual.

Aunque estos indicadores no representan medidas directas de percepción auditiva, permiten describir componentes observables del comportamiento infantil y ampliar la representación del fenómeno estudiado.

---

# K.5 APRENDIZAJE AUTOMÁTICO PARA CLASIFICACIÓN DE PATRONES OBSERVABLES

## K.5.1 Representación mediante características

Los modelos de aprendizaje automático requieren transformar la información obtenida desde datos originales en variables cuantificables.

En análisis de video, esta transformación puede incluir características relacionadas con movimiento, geometría facial, cambios temporales y relaciones espaciales.

La selección adecuada de características influye directamente en la capacidad del modelo para identificar patrones dentro de los datos.

---

## K.5.2 Clasificación mediante Random Forest

Random Forest es un algoritmo de aprendizaje supervisado basado en la combinación de múltiples árboles de decisión.

Cada árbol realiza una clasificación independiente utilizando diferentes subconjuntos de datos y variables. Posteriormente, los resultados individuales son combinados para obtener una decisión final [46].

Este algoritmo presenta ventajas para trabajar con conjuntos de datos multidimensionales, variables heterogéneas y relaciones no lineales.

Además, permite obtener medidas relacionadas con la importancia relativa de las características utilizadas durante la clasificación.

---

# K.6 REVISIÓN COMPLEMENTARIA DEL ESTADO DEL ARTE

La revisión estructurada de literatura permitió identificar avances relacionados con cuatro áreas principales:

- Evaluación auditiva infantil y dispositivos tecnológicos.
- Análisis computacional del comportamiento infantil.
- Visión por computador aplicada a registros audiovisuales.
- Aprendizaje automático aplicado a clasificación de patrones.

Los estudios revisados evidencian una tendencia hacia sistemas más portátiles, automatizados y orientados al análisis cuantitativo de información biomédica.

Sin embargo, se identifica que gran parte de los desarrollos existentes se concentran en componentes específicos, como mediciones fisiológicas auditivas o análisis independiente del comportamiento infantil.

La integración de adquisición audiovisual, extracción automática de características, clasificación computacional y organización estructurada de registros constituye un área de interés para el desarrollo de herramientas tecnológicas complementarias.

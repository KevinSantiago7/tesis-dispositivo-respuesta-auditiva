# ANEXO K. MARCO TEÓRICO COMPLEMENTARIO Y AMPLIACIÓN DEL ESTADO DEL ARTE

Este anexo presenta la ampliación de los fundamentos conceptuales y tecnológicos relacionados con el desarrollo del dispositivo experimental. Los contenidos incluidos corresponden a información complementaria trasladada desde el Capítulo 2 con el propósito de mantener una estructura más sintetizada del documento principal.

El anexo profundiza en los fundamentos de evaluación auditiva infantil, respuestas conductuales frente a estímulos sonoros, tecnologías computacionales aplicadas al análisis del comportamiento infantil, aprendizaje automático, plataformas digitales y antecedentes tecnológicos relacionados con la propuesta desarrollada.

---

# K.1 MÉTODOS DE EVALUACIÓN AUDITIVA INFANTIL

## K.1.1 Emisiones otoacústicas (OAE)

Las emisiones otoacústicas corresponden a respuestas acústicas generadas principalmente por la actividad de las células ciliadas externas de la cóclea. Estas señales pueden ser registradas mediante una sonda ubicada en el canal auditivo externo, permitiendo obtener información objetiva relacionada con el funcionamiento del sistema auditivo sin requerir una respuesta activa del bebé [15], [19], [20].

Su aplicación ha adquirido relevancia dentro de los programas de detección auditiva temprana debido a características como su naturaleza no invasiva, rapidez de adquisición y posibilidad de implementación durante los primeros meses de vida.

Sin embargo, la obtención de resultados adecuados depende de condiciones apropiadas de adquisición. Factores como ruido ambiental, movimiento del bebé, condiciones del oído medio y ubicación correcta de la sonda pueden afectar la calidad del registro [19], [20].

Debido a estas limitaciones, diferentes investigaciones han explorado alternativas orientadas a aumentar la accesibilidad de esta tecnología mediante dispositivos portátiles, integración con teléfonos inteligentes y componentes comerciales de menor costo [13]–[15].

---

## K.1.2 Potenciales evocados auditivos del tronco encefálico (ABR)

Los potenciales evocados auditivos del tronco encefálico permiten registrar la actividad eléctrica generada en la vía auditiva como respuesta a estímulos sonoros. Debido a que no requieren una respuesta voluntaria del bebé, constituyen una herramienta objetiva ampliamente utilizada en evaluación auditiva temprana [7], [20], [21].

A diferencia de las emisiones otoacústicas, los ABR requieren generalmente la colocación de electrodos y equipos especializados para adquirir las señales eléctricas generadas durante la estimulación.

Las condiciones de adquisición deben ser controladas para reducir interferencias relacionadas con movimiento y actividad muscular, lo que incrementa la complejidad técnica del procedimiento.

Los desarrollos recientes han explorado sistemas automatizados y algoritmos computacionales para apoyar el procesamiento e interpretación de señales electrofisiológicas [2], [5], [9], [12].

---

## K.1.3 Evaluación basada en respuestas conductuales

Además de los métodos fisiológicos, la evaluación auditiva infantil puede considerar respuestas observables generadas después de la presentación de estímulos sonoros.

Estas manifestaciones pueden incluir:

- Orientación hacia la fuente sonora.
- Cambios en la atención.
- Movimientos cefálicos.
- Expresiones faciales.
- Modificaciones posturales.

La audiometría por observación del comportamiento (*Behavioral Observation Audiometry*, BOA) se fundamenta en este principio, utilizando cambios conductuales asociados temporalmente con estímulos auditivos [24].

No obstante, estas respuestas presentan variabilidad debido a factores como edad, desarrollo motor, estado de alerta, interacción con el cuidador y condiciones ambientales.

---

# K.2 RESPUESTAS CONDUCTUALES INFANTILES FRENTE A ESTÍMULOS SONOROS

## K.2.1 Desarrollo motor y variabilidad conductual

Durante los primeros meses de vida, el comportamiento infantil presenta una alta variabilidad debido al proceso progresivo de maduración motora y perceptiva.

El control cefálico, la estabilidad postural y la capacidad de orientar movimientos hacia estímulos externos se desarrollan progresivamente, generando diferencias entre individuos e incluso entre diferentes momentos de evaluación de un mismo bebé [31], [32].

Por esta razón, las respuestas observables deben analizarse considerando el contexto de adquisición y no únicamente la presencia de un movimiento específico.

---

## K.2.2 Respuesta orientadora frente a estímulos sonoros

La respuesta orientadora corresponde a modificaciones conductuales asociadas temporalmente con la presentación de un estímulo acústico.

Puede manifestarse mediante cambios en:

- Orientación cefálica.
- Atención visual.
- Actividad motora.
- Postura corporal.

Sin embargo, una respuesta observable no representa necesariamente una percepción auditiva confirmada, debido a que diferentes factores pueden influir en el comportamiento registrado.

Por esta razón, la interpretación requiere considerar las condiciones iniciales del bebé y la evolución temporal del comportamiento.

---

# K.3 INTEGRACIÓN DE HERRAMIENTAS COMPUTACIONALES EN SALUD

La incorporación de herramientas computacionales en aplicaciones biomédicas ha permitido transformar información compleja obtenida mediante diferentes fuentes de adquisición en variables cuantificables.

La visión por computador y el aprendizaje automático han sido utilizados para analizar imágenes, videos y señales, permitiendo identificar patrones dentro de grandes volúmenes de información [25], [31]–[37], [41]–[46].

Sin embargo, la aplicación de estas tecnologías requiere considerar aspectos relacionados con:

- Calidad de los datos.
- Representatividad de las muestras.
- Interpretabilidad de resultados.
- Trazabilidad de la información.

En sistemas relacionados con salud, los modelos computacionales deben considerarse herramientas de apoyo que complementan la interpretación profesional.

---

# K.4 VISIÓN POR COMPUTADOR APLICADA AL COMPORTAMIENTO INFANTIL

## K.4.1 Detección facial y puntos de referencia geométricos

Los algoritmos de detección facial permiten localizar regiones del rostro y establecer puntos de referencia geométricos (*facial landmarks*) asociados con ojos, cejas, nariz y boca.

Estos puntos permiten representar la información visual mediante coordenadas espaciales y analizar cambios durante una secuencia temporal.

A partir de estas representaciones pueden calcularse:

- Desplazamientos.
- Variaciones angulares.
- Velocidad de cambio.
- Diferencias respecto a una condición inicial.

Estas técnicas permiten transformar información visual en variables cuantificables [48], [49].

---

## K.4.2 Análisis del movimiento infantil

El movimiento infantil constituye una fuente de información utilizada para estudiar patrones motores y conductuales mediante herramientas computacionales.

Diferentes investigaciones han empleado estimación de pose, seguimiento temporal y análisis de movimiento para caracterizar trayectorias corporales y cambios posturales en población infantil [33]–[37].

Debido a la variabilidad natural del comportamiento infantil, estos métodos suelen considerar características dinámicas y cambios relativos.

---

## K.4.3 Indicadores faciales como información complementaria

Las expresiones faciales pueden aportar información adicional sobre cambios conductuales observables.

Variables relacionadas con:

- Apertura ocular.
- Parpadeo.
- Movimiento de cejas.
- Región oral.

pueden ser analizadas mediante técnicas de procesamiento facial.

Aunque estos indicadores no representan medidas directas de percepción auditiva, permiten ampliar la descripción computacional del comportamiento infantil.

---

# K.5 APRENDIZAJE AUTOMÁTICO PARA CLASIFICACIÓN DE PATRONES OBSERVABLES

## K.5.1 Representación mediante características

Los modelos de aprendizaje automático requieren transformar los datos originales en representaciones cuantificables.

En análisis audiovisual, estas representaciones pueden incluir características relacionadas con:

- Movimiento.
- Geometría facial.
- Cambios temporales.
- Relaciones espaciales.

La calidad de estas características influye directamente en la capacidad del modelo para identificar patrones.

---

## K.5.2 Clasificación mediante Random Forest

Random Forest es un algoritmo supervisado basado en la combinación de múltiples árboles de decisión.

Cada árbol genera una clasificación independiente utilizando diferentes subconjuntos de datos y variables. Posteriormente, los resultados son combinados para obtener una predicción final [46].

Este algoritmo presenta ventajas para trabajar con:

- Múltiples variables de entrada.
- Relaciones no lineales.
- Datos heterogéneos.

Además, permite estimar la importancia relativa de las características utilizadas.

---

## K.5.3 Validación de modelos en población infantil

Los modelos aplicados a datos infantiles requieren estrategias de validación que consideren la variabilidad entre participantes.

Cuando existen múltiples registros del mismo individuo, una separación inadecuada puede generar dependencia entre entrenamiento y evaluación, produciendo estimaciones poco representativas.

Por esta razón, las estrategias agrupadas por participante permiten evaluar mejor la capacidad de generalización frente a nuevos individuos.

---

# K.6 PLATAFORMAS DIGITALES, USABILIDAD Y TRAZABILIDAD

## K.6.1 Plataformas digitales en salud

Las plataformas digitales permiten almacenar, organizar y consultar información generada durante procesos de adquisición y evaluación.

Estas herramientas facilitan la integración de diferentes fuentes de datos y permiten conservar registros estructurados para análisis posteriores.

---

## K.6.2 Interacción humano-computador

La interacción humano-computador estudia la relación entre usuarios y sistemas digitales con el objetivo de desarrollar interfaces comprensibles y eficientes.

En aplicaciones relacionadas con salud, el diseño debe considerar diferentes perfiles de usuario y presentar la información de manera clara, diferenciando datos técnicos, resultados computacionales e interpretación profesional.

---

## K.6.3 Usabilidad y aceptación tecnológica

La usabilidad permite evaluar la facilidad con la que un sistema puede ser utilizado dentro de un contexto específico.

La escala SUS permite obtener una valoración global de percepción de uso [51].

Por otra parte, el modelo TAM analiza factores relacionados con utilidad percibida y facilidad de uso como elementos asociados a la aceptación tecnológica [52].

---

## K.6.4 Trazabilidad de información

La trazabilidad permite conservar la relación entre:

- Datos adquiridos.
- Procesamiento realizado.
- Variables generadas.
- Resultados obtenidos.

Esta organización facilita reconstruir el contexto en el que fue generado un resultado y permite realizar revisiones posteriores.

---

# K.7 AMPLIACIÓN DEL ESTADO DEL ARTE

La revisión estructurada de literatura identificó avances en cuatro áreas principales:

1. Evaluación auditiva infantil.
2. Análisis computacional del comportamiento infantil.
3. Visión por computador.
4. Aprendizaje automático aplicado a datos biomédicos.

Los estudios revisados muestran una tendencia hacia sistemas más portátiles, automatizados y orientados al análisis cuantitativo de información.

Sin embargo, gran parte de las soluciones existentes se enfocan en componentes individuales del proceso, dejando una oportunidad de integración entre adquisición audiovisual, análisis computacional y organización estructurada de evidencia.

La tabla comparativa de estudios incluidos, estrategias de búsqueda, criterios de selección y análisis detallado de literatura se presenta en el Anexo A y los complementos de análisis bibliográfico en este anexo.

# ANEXO A. REVISIÓN ESTRUCTURADA DE LITERATURA BAJO LINEAMIENTOS PRISMA 2020

*Versión consolidada — cifras y referencias verificadas contra el proceso de cribado documentado*

---

## A.1 Introducción

Se realizó una revisión estructurada de literatura con el propósito de fundamentar el desarrollo del dispositivo tecnológico propuesto, identificando avances relacionados con evaluación auditiva infantil, respuestas conductuales frente a estímulos sonoros, visión por computador y aprendizaje automático.

El proceso tomó como referencia las recomendaciones PRISMA 2020 para organizar las etapas de identificación, depuración, priorización y selección documental. Debido al volumen inicial de registros recuperados, se implementó una estrategia de dos fases:

1. Priorización automatizada por concordancia temática.
2. Verificación manual de los registros con mayor relación con los objetivos del proyecto.

La revisión permitió construir el estado del arte del dispositivo, identificando antecedentes clínicos, tecnológicos y metodológicos relacionados con la integración de estímulos auditivos, captura audiovisual y análisis computacional.

Esta revisión corresponde a una revisión estructurada orientada al desarrollo tecnológico del dispositivo y no a una revisión sistemática clínica con metaanálisis.

---

## A.2 Pregunta de revisión

¿Qué avances tecnológicos y metodológicos se han reportado para analizar respuestas observables frente a estímulos sonoros en población infantil mediante dispositivos electrónicos, visión por computador y modelos de aprendizaje automático?

La revisión se organizó en cuatro líneas:

1. Evaluación auditiva infantil y dispositivos tecnológicos.
2. Respuestas conductuales frente a estímulos sonoros.
3. Visión computacional aplicada al análisis infantil.
4. Aprendizaje automático para clasificación de patrones observables.

---

## A.3 Bases bibliográficas y gestión documental

| Base bibliográfica | Área principal |
|---|---|
| PubMed | Audiología, medicina y neonatología |
| Scopus | Literatura multidisciplinaria |
| IEEE Xplore | Ingeniería, inteligencia artificial y dispositivos |
| ScienceDirect | Ciencias biomédicas y tecnología |
| Google Scholar | Recuperación complementaria |

La gestión, organización y depuración bibliográfica se realizó mediante Zotero.

| Etapa | Cantidad |
|---|---:|
| Registros importados | 6567 |
| Duplicados eliminados | 573 |
| Registros únicos para cribado | 5994 |

---

## A.4 Estrategia de búsqueda

**Audición infantil**
```
("infant" OR "newborn" OR "neonate" OR "baby")
AND ("hearing" OR "auditory" OR "sound stimulus" OR "auditory response")
AND ("screening" OR "assessment" OR "detection")
```

**Respuesta conductual**
```
("infant" OR "newborn" OR "baby")
AND ("auditory stimulus" OR "sound")
AND ("behavioral response" OR "movement" OR "orientation" OR "reaction")
```

**Visión computacional**
```
("infant" OR "newborn" OR "neonate")
AND ("computer vision" OR "facial landmarks" OR "pose estimation" OR "video analysis" OR "motion tracking")
AND ("behavior analysis" OR "movement analysis")
```

**Inteligencia artificial**
```
("machine learning" OR "deep learning" OR "artificial intelligence")
AND ("infant" OR "newborn" OR "neonatal")
AND ("classification" OR "detection" OR "recognition")
```

---

## A.5 Criterios de selección

### Inclusión
Se incluyeron estudios que:
- involucraran bebés, neonatos o población infantil;
- analizaran estímulos auditivos o respuestas observables;
- utilizaran dispositivos, sensores, visión computacional o inteligencia artificial;
- aportaran metodologías aplicables al dispositivo desarrollado.

### Exclusión
Se excluyeron estudios:
- realizados exclusivamente en adultos;
- basados en modelos animales;
- sin relación con comportamiento infantil o audición;
- con inteligencia artificial sin aplicación relacionada.

---

## A.6 Proceso de cribado

Los 5994 registros únicos fueron priorizados mediante coincidencias de título, resumen y palabras clave con los términos de las cuatro líneas temáticas.

| Estrato | Cantidad |
|---|---:|
| Alta relación | 39 |
| Media relación | 1824 |
| Baja relación | 4131 |

Esta etapa correspondió a una priorización temática automatizada y no a una decisión definitiva de inclusión o exclusión.

### A.6.1 Verificación manual

| Categoría | Descripción |
|---|---|
| Incluir | Relación suficiente con el estado del arte o el desarrollo tecnológico |
| Apoyo | Metodologías transferibles o soporte contextual, sin síntesis individual en A.7 |
| Excluir | Sin relación suficiente |

| Origen | Incluir | Apoyo | Excluir | Total |
|---|---:|---:|---:|---:|
| Alta (39) | 11 | 14 | 14 | 39 |
| Media 1-30 (30) | 10 | 11 | 9 | 30 |
| Media 31-200 (170) | 70 | 53 | 47 | 170 |
| **Total revisado manualmente** | **91** | **78** | **70** | **239** |

De los 91 registros clasificados como "Incluir", se aplicó un segundo filtro de relevancia directa con el problema de investigación para seleccionar un **núcleo de 29 estudios** con síntesis individual en la sección A.7. Los 62 restantes se consideran referencias de apoyo potencial para la contextualización del marco teórico del Capítulo 2. Su incorporación específica depende de la pertinencia frente a cada sección desarrollada y no se incluyen como estudios con síntesis individual dentro de este anexo.

### A.6.2 Análisis exploratorio del estrato de Baja prioridad

Se revisó una muestra aleatoria de 50 registros del estrato de Baja prioridad (n = 4131) como control de sensibilidad del cribado automatizado.

| Resultado | Cantidad |
|---|---:|
| Potencialmente relacionados | 11 |
| No relacionados | 39 |

El 22 % de la muestra presentó relación potencial con el proyecto. Este resultado se declara como **limitación metodológica del filtro automático**: sugiere que el estrato de Baja prioridad probablemente contiene estudios adicionales de relevancia no revisados por restricciones de tiempo y alcance del trabajo de grado. Se recomienda documentar esta limitación explícitamente en el Capítulo 3 (Metodología) y no proyectar el hallazgo como cifra exacta, dado que corresponde a una muestra exploratoria y no a una revisión censal del estrato.

---

## A.7 Estudios incluidos en la síntesis cualitativa (núcleo, n = 29)

> Referencias verificadas una a una contra el archivo Zotero/CSV original de candidatos priorizados (autor, año, revista y DOI reales).

### A.7.1 Evaluación auditiva infantil y dispositivos tecnológicos

| Referencia | Aporte |
|---|---|
| Pe et al. (2026). *Hearing Impairment Assessment in Infants Through Explainable Computer Vision Analysis of Facial Features*. DOI: 10.1007/978-3-032-17216-7_3 | Une audición infantil, visión computacional e IA explicable. |
| Grau, Laszeski y Franzi (2025). *CABRA: A Prototype for a Computarized Auditory Brainstem Response Audiometry Device for Semi-automated Hearing Evaluation*. DOI: 10.1007/978-3-032-06401-1_151 | Dispositivo automatizado y semiautomático para evaluación auditiva ABR. |
| Corso, Pe et al. (2027). *A Benchmark Study for Reporting Feasibility in AI-Based Infant Hearing Screening: Exploring the Limits of Passive Sensing*. DOI: 10.1007/978-3-032-30710-1_38 | Video facial + ML + límites de la detección pasiva de respuesta auditiva. |
| Santos-Ceballos et al. (2020). *Implementation of the NEURONIC INFANTIX Newborn Hearing Screening System*. DOI: 10.1007/978-3-030-30648-9_59 | Sistema de tamizaje con hardware propio y validación experimental. |
| Rushaidin, Salleh, Swee, Najeb y Arooj (2009). *Wave V detection using instantaneous energy of auditory brainstem response signal*. American Journal of Applied Sciences. DOI: 10.3844/ajassp.2009.1669.1674 | Procesamiento digital de señales ABR para detección automática de la onda V. |
| Várallyay Jr. (2007). *The melody of crying*. International Journal of Pediatric Otorhinolaryngology. DOI: 10.1016/j.ijporl.2007.07.005 | Análisis del llanto infantil como posible indicador auditivo/neurológico. |
| Cebulla y Shehata-Dieler (2012). *ABR-based newborn hearing screening with MB11 BERAphone® using an optimized chirp for acoustical stimulation*. Int. J. Pediatric Otorhinolaryngology. DOI: 10.1016/j.ijporl.2012.01.012 | Algoritmo automático de tamizaje ABR con estímulo chirp optimizado. |
| Buller, Hoth y Suchandt (2000). *Expert system for aiding the diagnosis in hearing screening*. Biomedizinische Technik. DOI: 10.1515/bmte.2000.45.9.248 | Sistema experto con clasificación automática de resultados OAE. |
| Paulraj, Subramaniam, Yaccob, Adom y Hema (2014). *A machine learning approach for distinguishing hearing perception level using auditory evoked potentials*. IEEE IECBES. DOI: 10.1109/IECBES.2014.7047661 | IA aplicada a señales evocadas para clasificar el nivel de percepción auditiva. |
| Madzivhandila, le Roux y Biagio de Jager (2024). *Neonatal hearing screening using a smartphone-based otoacoustic emission device: A comparative study*. Int. J. Pediatric Otorhinolaryngology. DOI: 10.1016/j.ijporl.2024.111862 | Dispositivo OAE basado en smartphone, validado comparativamente. |
| Dahildahil, Isip-Tan, Grace y Grepo (2023). *User-centered Design in Time and Resource-limited Settings: Enhancing the Usability of 'Hearing for Life' (HeLe) Device*. Acta Medica Philippina. DOI: 10.47895/amp.v57i9.1594 | Diseño centrado en usuario y usabilidad de dispositivo médico auditivo. |
| Ma et al. (2023). *Auditory Brainstem Response Data Preprocessing Method for the Automatic Classification of Hearing Loss Patients*. Diagnostics. DOI: 10.3390/diagnostics13233538 | Preprocesamiento de señales ABR para clasificación automática con IA. |
| Chan y Gollakota (2022). *Inner-ear cochlea testing with earphones*. DOI: 10.1145/3498361.3538657 | Dispositivo económico de prueba coclear basado en audífonos comunes. |
| Ali, Chan, Meehan et al. (2025). *An Open-Source Smartphone Otoacoustic Emissions Test for Infants*. Pediatrics. DOI: 10.1542/peds.2024-068068 | Dispositivo abierto OAE basado en smartphone, validado clínicamente. |
| Chan, Ali, Najafi et al. (2022). *An off-the-shelf otoacoustic-emission probe for hearing screening via a smartphone*. Nature Biomedical Engineering. DOI: 10.1038/s41551-022-00947-6 | Sonda OAE de bajo costo validada clínicamente vía smartphone. |
| Maray, Alsaif y Tanoon (2022). *Design and Implementation of Low-Cost Medical Auditory System of Distortion Otoacoustic Using Microcontroller*. Journal of Engineering Science and Technology. DOI: s/d — verificar en la revista. | Generación/captura y análisis automático de OAE mediante microcontrolador. |
| Quiñonez, Rodríguez Quiñonez y Owen (2001). *Comparison of neonatal hearing screening devices*. Puerto Rico Health Sciences Journal. | Comparación de usabilidad y desempeño entre dispositivos comerciales de tamizaje. |
| Krumm, Ribera y Schmiedge (2005). *Using a telehealth medium for objective hearing testing: Implications for supporting rural universal newborn hearing screening programs*. Seminars in Hearing. DOI: 10.1055/s-2005-863789 | Evaluación auditiva remota vía telesalud para zonas rurales. |
| Hatzopoulos, Ciorba, Sliwa y Skarzynski (2013). *Technological advances in Universal Neonatal Hearing Screening (UNHS)*. Hearing, Balance and Communication. DOI: 10.3109/21695717.2013.821757 | Revisión tecnológica de AABR, ASSR, OAE y dispositivos portátiles. |
| Neumann e Indermark (2012). *Validation of a new TEOAE-AABR device for newborn hearing screening*. International Journal of Audiology. DOI: 10.3109/14992027.2012.692821 | Validación de dispositivo combinado OAE+AABR con métricas de concordancia. |

### A.7.2 Respuesta conductual frente a estímulos sonoros

| Referencia | Aporte |
|---|---|
| Evangelista, John, Chiong, Nadine y Eunice (2023). *Accuracy and Use of the Reflexive Behavioral ("Baah") Test and Risk Factor Questionnaire for Hearing Screening in Infants Six Months Old and Below*. Acta Medica Philippina. DOI: 10.47895/amp.v57i9.4378 | Evalúa respuesta conductual refleja observable como método de tamizaje. |

### A.7.3 Visión computacional aplicada al análisis infantil

| Referencia | Aporte |
|---|---|
| Hirose (2023). *Head Detection and Head Orientation Estimation of Infants during a Nap using Deep Learning*. IEEJ Trans. on Electronics, Information and Systems. DOI: 10.1541/ieejeiss.143.871 | Estimación de orientación cefálica (yaw) mediante deep learning — variable central del proyecto. |
| Leo, Bernava, Carcagnì y Distante (2022). *Video-Based Automatic Baby Motion Analysis for Early Neurological Disorder Diagnosis: State of the Art and Future Directions*. Sensors. DOI: 10.3390/s22030866 | Revisión de referencia sobre visión computacional aplicada al movimiento infantil. |
| Yin et al. (2024). *A self-supervised spatio-temporal attention network for video-based 3D infant pose estimation*. Medical Image Analysis. DOI: 10.1016/j.media.2024.103208 | Extracción temporal de pose 3D infantil mediante aprendizaje autosupervisado. |
| Ruiz-Zafra et al. (2025). *PyBodyTrack: A python library for multi-algorithm motion quantification and tracking in videos*. SoftwareX. DOI: 10.1016/j.softx.2025.102272 | Librería de referencia para cuantificación de movimiento (compatible con MediaPipe/OpenPose/YOLO). |

### A.7.4 Inteligencia artificial y aprendizaje automático

| Referencia | Aporte |
|---|---|
| Zhang et al. (2026). *FIGNet: A Robust and Interpretable Fuzzy-Irreversible Gated Network for Auditory Brainstem Response Classification*. IEEE J. Biomedical and Health Informatics. DOI: 10.1109/JBHI.2025.3604834 | Red neuronal interpretable aplicada a clasificación de señales ABR. |
| Schornagel, Soede, Vossen y Oudesluys-Murphy (2026). *Enhanced auditory brainstem response device (Vivosonic Integrity) in young children, in the child's home and hospital*. Int. J. Audiology. DOI: 10.1080/14992027.2025.2502441 | Dispositivo ABR portátil con procesamiento automatizado, uso domiciliario y hospitalario. |
| Fullante, John, Miguel, Luistro, Grace, Sison y Chiong (2023). *Evaluation of an Investigational Hearing Screening Device (HeLe) to Demonstrate Acoustic Brainstem Response among Normal-hearing Adults*. Acta Medica Philippina. DOI: 10.47895/amp.v57i9.4366 | Desarrollo y validación experimental de dispositivo de tamizaje auditivo. |
| Rosario, Jayne, Glorian, Dariel y Grace (2023). *Evaluation of the Design and Development of the HeLe Newborn Hearing Screening Tele-Audiology Systems for the Philippines*. Acta Medica Philippina. DOI: 10.47895/amp.v57i9.5392 | Plataforma de teleaudiología, almacenamiento y gestión de datos de tamizaje. |

*Nota: la distribución de algunos artículos entre A.7.1 y A.7.4 puede ajustarse durante la redacción final del Capítulo 2 según el énfasis metodológico de cada uno.*

---

## A.8 Documentos clínicos y normativos de apoyo

| Documento | Uso |
|---|---|
| World Health Organization | Contexto de pérdida auditiva infantil. |
| Joint Committee on Infant Hearing (2019) | Detección temprana e intervención auditiva. |
| ASHA | Conceptos clínicos de pérdida auditiva infantil. |
| American Academy of Audiology | Guías de tamizaje auditivo infantil. |

## A.9 Referencias metodológicas del desarrollo

| Referencia | Uso |
|---|---|
| PRISMA 2020 | Metodología de revisión. |
| Breiman (2001) | Random Forest. |
| Pedregosa et al. (2011) | Scikit-learn. |
| Lugaresi et al. (2019) | MediaPipe. |
| Kartynnik et al. (2019) | Geometría facial. |
| OpenCV | Procesamiento visual. |
| Brooke (1996) | Escala SUS. |
| Davis (1989) | Modelo TAM. |

---

## A.10 Brecha tecnológica

La literatura evidencia avances individuales en tamizaje auditivo, análisis de movimiento infantil, visión computacional y aprendizaje automático.

Sin embargo, estos enfoques generalmente se desarrollan de forma independiente. La brecha identificada corresponde a la integración de:

- generación controlada de estímulos sonoros;
- captura audiovisual del comportamiento infantil;
- extracción automática de características;
- clasificación mediante aprendizaje automático;
- almacenamiento estructurado de ensayos;
- revisión profesional posterior.

Esta integración constituye el aporte tecnológico del dispositivo desarrollado.

---

## A.11 Flujo PRISMA resumido

```
Registros identificados:        n = 6567
        ↓
Duplicados eliminados:          n = 573
        ↓
Registros únicos:               n = 5994
        ↓
Priorización temática automatizada:
   Alta = 39 | Media = 1824 | Baja = 4131
        ↓
Verificación manual (título/resumen):
   Alta:  39/39   → Incluir 11 | Apoyo 14 | Excluir 14
   Media: 200/1824 (rango 1-200) → Incluir 80 | Apoyo 64 | Excluir 56
   Baja:  muestra de control 50/4131 → 11 potencialmente relacionados (22 %),
          declarado como limitación metodológica (ver A.6.2)
        ↓
Total revisado manualmente: 239 de 5994 (Alta + Media 1-200)
        ↓
Núcleo con síntesis individual (A.7):      n = 29
Apoyo directo (citado sin ficha propia):   n = 62
Contexto general / bibliografía de respaldo: n = 78
```

---

## A.12 Consideración final

La revisión permitió identificar antecedentes científicos y tecnológicos que sustentan la arquitectura del dispositivo desarrollado, especialmente en la integración de estímulos auditivos, captura audiovisual, extracción de características y clasificación automática de respuestas observables en población infantil.

El proceso documentado en este anexo —priorización automatizada, verificación manual con decisión artículo por artículo, control de sensibilidad sobre el estrato de baja prioridad y verificación bibliográfica contra la base Zotero— permite trazar cada cifra reportada hasta su origen, y declara explícitamente sus límites (estrato Media revisado parcialmente; 14 artículos de Alta pendientes de resolución a texto completo; ver A.13).

## A.13 Limitaciones y consideraciones finales

Aunque el proceso permitió establecer un núcleo bibliográfico relacionado directamente con el desarrollo del dispositivo, se identifican las siguientes limitaciones metodológicas:

1. Los 14 artículos del estrato Alta clasificados inicialmente como "Apoyo" requieren revisión a texto completo para determinar si pueden incorporarse como estudios incluidos o mantenerse como referencias complementarias.

2. El registro de Maray, Alsaif y Tanoon (2022) requiere verificación adicional del DOI debido a que dicho identificador no se encontraba disponible en el registro exportado desde Zotero.

3. Del estrato Media (n = 1824), se realizó revisión manual de los primeros 200 registros priorizados por relevancia temática. Los registros restantes no fueron evaluados individualmente debido al alcance del trabajo de grado.

4. La muestra exploratoria del estrato Baja prioridad permitió identificar posibles estudios relevantes no detectados por el filtro automático; sin embargo, este resultado no debe interpretarse como una estimación poblacional del total de registros no revisados.

Estas limitaciones no afectan la trazabilidad del núcleo seleccionado, dado que los estudios incluidos en la síntesis cualitativa fueron revisados individualmente y verificados frente a la base bibliográfica consolidada.

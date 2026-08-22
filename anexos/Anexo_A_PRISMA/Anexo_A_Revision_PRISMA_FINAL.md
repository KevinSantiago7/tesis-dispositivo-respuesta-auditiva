# ANEXO A. REVISIÓN ESTRUCTURADA DE LITERATURA BAJO LINEAMIENTOS PRISMA 2020


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
| Apoyo | Metodologías transferibles o soporte contextual |
| Excluir | Sin relación suficiente |

**Estrato Alta (39/39, 100 % revisado)**

| Origen | Incluir | Apoyo | Excluir | Total |
|---|---:|---:|---:|---:|
| Alta | 11 | 14 | 14 | 39 |

**Estrato Media — revisión completa del rango 400 (de 1824)**

| Rango | Incluir | Apoyo | Excluir | Total |
|---|---:|---:|---:|---:|
| **Media** | **140** | **119** | **141** | **400** |

Media 400 representa el 21,9 % del estrato Media (400/1824).

**Consolidado Alta + Media (revisión manual completa, artículo por artículo)**

| Origen | Incluir | Apoyo | Excluir | Total |
|---|---:|---:|---:|---:|
| Alta (39) | 11 | 14 | 14 | 39 |
| Media (400) | 140 | 119 | 141 | 400 |
| **Total** | **151** | **133** | **155** | **439** |

**439 de 5994 registros (7,3 %) fueron revisados manualmente artículo por artículo**, con decisión y justificación individual registrada para cada uno.

De los 151 registros clasificados como "Incluir" en Alta + Media, se aplicó un segundo filtro de relevancia directa con el problema de investigación para seleccionar un **núcleo de 59 estudios** con síntesis individual en la sección A.7, verificados uno a uno contra la base Zotero/CSV en esta consolidación — ver A.7.5 y A.7.6). Los 92 registros "Incluir" restantes, junto con los 133 clasificados como "Apoyo", se consideran referencias de apoyo potencial para la contextualización del marco teórico del Capítulo 2. Su incorporación específica depende de la pertinencia frente a cada sección desarrollada y no se incluyen como estudios con síntesis individual dentro de este anexo.

### A.6.2 Análisis exploratorio del estrato de Baja prioridad


| Tanda | Relacionados | No relacionados | Total |
|---|---:|---:|---:|

| **Total n = 150** | **42** | **108** | **150** |


---

## A.7 Estudios incluidos en la síntesis cualitativa (núcleo, n = 59)

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

### A.7.5 Ampliación del núcleo — Pose, movimiento y postura infantil (n = 14)

> Candidatos identificados en Media 201-400, verificados uno a uno contra la base Zotero/CSV en esta consolidación.

| Referencia | Aporte |
|---|---|
| Lin, Jeng, Chandra, Tsao, Liao, Chen, Yen y Hsu (2024). *Application of Artificial Intelligence in Infant Movement Classification: A Reliability and Validity Study in Infants Who Were Full-Term and Preterm*. Physical Therapy. DOI: 10.1093/ptj/pzad176 | IA aplicada a clasificación de movimiento infantil, validada en población a término y prematura. |
| Ari, Atalan Efkere y Çangur (2026). *Automatic Infant Movement Assessment Using Pose-LBP Features and a Cost-Sensitive Subspace kNN Ensemble*. Bioengineering. DOI: 10.3390/bioengineering13050516 | Extracción de características de pose + LBP para clasificación automática de movimiento. |
| Migliorelli, Moccia, Pietrini et al. (2020). *The babyPose dataset*. Data in Brief. DOI: 10.1016/j.dib.2020.106329 | Dataset de referencia de pose infantil, útil como base metodológica de comparación. |
| Ksibi, Ayadi, Elmannai, Hamdi et al. (2026). *TransCP-Net: Transformer-Based Spatiotemporal Pose Representation for Early Screening of Infant Cerebral Palsy*. CMES. DOI: 10.32604/cmes.2026.078347 | Arquitectura tipo Transformer sobre secuencias de pose infantil. |
| Soualmi, Alata, Ducottet et al. (2026). *3D machine learning-based complexity variability and fluidity quantification of preterm and writhing general movements*. Computers in Biology and Medicine. DOI: 10.1016/j.compbiomed.2026.111815 | ML 3D aplicado a cuantificación de movimientos generales (writhing) en prematuros. |
| McCay, Ho, Shum, Fan et al. (2020). *Abnormal Infant Movements Classification with Deep Learning on Pose-Based Features*. IEEE Access. DOI: 10.1109/ACCESS.2020.2980269 | Clasificación de movimientos anormales a partir de características de pose. |
| Doroniewicz, Ledwoń, Affanasowicz et al. (2020). *Writhing movement detection in newborns on the second and third day of life using pose-based features*. Sensors. DOI: 10.3390/s20215986 | Detección de movimientos writhing neonatales mediante características de pose. |
| Kaur, Abbasi, Williams y Baxter (2025). *Training General Movements Classifiers with Global Labels Offers Insights on Sub-movement Quality*. Proc. ICBES. DOI: 10.11159/icbes25.126 | Entrenamiento de clasificadores de movimientos generales con etiquetas globales. |
| McCay, Hu, Shum, Woo et al. (2022). *A Pose-Based Feature Fusion and Classification Framework for the Early Prediction of Cerebral Palsy*. IEEE TNSRE. DOI: 10.1109/TNSRE.2021.3138185 | Fusión de características de pose para predicción temprana. |
| Huang, Liu, Wan, Fu et al. (2023). *Appearance-Independent Pose-Based Posture Classification in Infants*. DOI: 10.1007/978-3-031-37660-3_2 | Clasificación de postura infantil independiente de la apariencia, solo a partir de pose. |
| Groos, Adde, Støen et al. (2022). *Towards human-level performance on automatic pose estimation of infant spontaneous movements*. Computerized Medical Imaging and Graphics. DOI: 10.1016/j.compmedimag.2021.102012 | Estimación automática de pose validada contra desempeño humano — referencia directa para la validación con expertos del dispositivo. |
| Moro, Pastore, Tacchino et al. (2022). *A markerless pipeline to analyze spontaneous movements of preterm infants*. Computer Methods and Programs in Biomedicine. DOI: 10.1016/j.cmpb.2022.107119 | Pipeline sin marcadores para análisis de motricidad espontánea neonatal. |
| Moro, Sigismondi, Gismondi et al. (2026). *Video-based computational analysis of spontaneous movements in preterm infants: A longitudinal neurodevelopmental study*. Computer Methods and Programs in Biomedicine. DOI: 10.1016/j.cmpb.2026.109412 | Análisis longitudinal por video de motricidad espontánea en prematuros. |
| Marino, Migliorelli, Moccia y Sirocchi (2024). *A Monitoring Support System Based on HRNet+DEKR for Neonatal Intensive Care Units*. DOI: no disponible en el registro exportado de Zotero — requiere verificación adicional (ver A.13). | Estimación de pose neonatal (keypoints) para monitoreo en UCIN. |

### A.7.6 Ampliación del núcleo — Dolor facial, estados fisiológicos y conducta infantil observable (n = 16)

> Candidatos identificados en Media 201-400, verificados uno a uno contra la base Zotero/CSV en esta consolidación.

| Referencia | Aporte |
|---|---|
| Baumgartl, Flathau, Bayerlein et al. (2021). *Pain level assessment for infants using facial expression scores*. IEEE COMPSAC. DOI: 10.1109/COMPSAC51774.2021.00087 | Clasificación de nivel de dolor a partir de puntajes de expresión facial. |
| Hausmann, Salekin, Zamzmi et al. (2024). *Accurate Neonatal Face Detection for Improved Pain Classification in the Challenging NICU Setting*. IEEE Access. DOI: 10.1109/ACCESS.2024.3383789 | Detección facial neonatal robusta como paso previo a clasificación de dolor. |
| Reyes-Hernández, Alomar, Rubio et al. (2024). *OBBabyFace: Oriented Bounding Box for Infant Face Detection*. DOI: 10.1007/978-3-031-66705-3_22 | Detección facial infantil con bounding box orientado. |
| Zhao, Zhu, Chen, Luo y Li (2023). *Pose-invariant and occlusion-robust neonatal facial pain assessment*. Computers in Biology and Medicine. DOI: 10.1016/j.compbiomed.2023.107462 | Evaluación facial de dolor robusta a oclusión y variabilidad de pose. |
| Hughes, Chivers, Hoti et al. (2023). *The Clinical Suitability of an Artificial Intelligence–Enabled Pain Assessment Tool for Use in Infants: Feasibility and Usability Evaluation Study* (PainChek). Journal of Medical Internet Research. DOI: 10.2196/41992 | Validación clínica de herramienta de IA para evaluación de dolor infantil; aporta métricas de usabilidad y aceptación profesional, transferibles a la validación del dispositivo propio. |
| Mukai, Morita, Shirai y Wakida (2019). *Automatic Classification of Neonatal Sleep-Wake States Based on Facial Video Analysis*. IEEE ICITR. DOI: 10.1109/ICITR49409.2019.9407788 | Clasificación automática de estados sueño-vigilia mediante video facial neonatal. |
| Cilia, Carette, Elbattah, Dequen et al. (2021). *Computer-Aided Screening of Autism Spectrum Disorder: Eye-Tracking Study Using Data Visualization and Deep Learning*. JMIR Human Factors. DOI: 10.2196/27706 | Seguimiento ocular infantil + deep learning para tamizaje conductual. |
| Varma, Washington, Chrisman, Kline et al. (2022). *Identification of Social Engagement Indicators Associated With Autism Spectrum Disorder Using a Game-Based Mobile App*. Journal of Medical Internet Research. DOI: 10.2196/31830 | Indicadores de compromiso social infantil capturados mediante video/app móvil. |
| Lysenko, Seethapathi, Prosser et al. (2020). *Towards Automated Emotion Classification of Atypically and Typically Developing Infants*. IEEE BioRob. DOI: 10.1109/BioRob49111.2020.9224271 | Clasificación automática de emociones en desarrollo típico/atípico infantil. |
| Zhu, Wan, Hatamimajoumerd, Jain et al. (2023). *A Video-Based End-to-end Pipeline for Non-nutritive Sucking Action Recognition and Segmentation in Young Infants*. DOI: 10.1007/978-3-031-43895-0_55 | Reconocimiento de conducta de succión no nutritiva mediante video. |
| Dorantes Olvera, López, Martinez y Rivera (2016). *Noninvasive monitoring system for early detection of apnea in newborns and infants*. IEEE EMBS Conference on Biomedical Engineering. DOI: 10.1109/IECBES.2016.7843500 | Monitoreo neonatal no invasivo mediante visión/sensores. |
| Fang, Liu y Zhang (2023). *Wi-Senser: Contactless Head Movement Detection during Sleep Utilizing WiFi Signals*. Applied Sciences. DOI: 10.3390/app13137572 | Detección de movimiento cefálico sin contacto, filosofía tecnológica afín. |
| Park y Hong (2023). *Facial Video-Based Robust Measurement of Respiratory Rates in Various Environmental Conditions*. Journal of Sensors. DOI: 10.1155/2023/9207750 | Medición de frecuencia respiratoria mediante video facial, robusta a condiciones ambientales. |
| Fitter, Funke, Pulido et al. (2020). *Toward Predicting Infant Developmental Outcomes from Day-Long Inertial Motion Recordings*. IEEE TNSRE. DOI: 10.1109/TNSRE.2020.3016916 | Predicción de resultados de desarrollo a partir de registros inerciales de movimiento. |
| Morais, Le, Tran, Alexander, Amery et al. (2026). *Confident and Trustworthy Model for Fidgety Movement Classification*. IEEE Journal of Biomedical and Health Informatics. DOI: 10.1109/JBHI.2025.3624341 | Modelo confiable con estimación de incertidumbre para clasificación de movimientos fidgety. |
| Raveendran y Madhu (2026). *A Systematic Analysis on Early Diagnosis of Cerebral Palsy in Infants by Harnessing Explainable AI Methods*. IEEE ICISS. DOI: 10.1109/ICISS67859.2026.11453738 | Revisión sistemática de IA explicable aplicada al diagnóstico temprano infantil. |

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
Verificación manual (título/resumen), sin vacíos:
   Alta:  39/39     → Incluir 11  | Apoyo 14  | Excluir 14
   Media: 400/1824  → Incluir 140 | Apoyo 119 | Excluir 141
   Baja:  muestra de control 150/4131 → 42 potencialmente relacionados (28,0 %),
          declarado como limitación metodológica (ver A.6.2)
        ↓
Total revisado manualmente artículo por artículo: 439 de 5994 (7,3 %)
        ↓
Núcleo con síntesis individual (A.7):        n = 59
Apoyo directo (Incluir sin ficha propia):    n = 92
Apoyo/contexto general (Apoyo, sin ficha):   n = 133
```

---

## A.12 Consideración final

La revisión permitió identificar antecedentes científicos y tecnológicos que sustentan la arquitectura del dispositivo desarrollado, especialmente en la integración de estímulos auditivos, captura audiovisual, extracción de características y clasificación automática de respuestas observables en población infantil.

El proceso documentado en este anexo —priorización automatizada, verificación manual con decisión artículo por artículo sobre el 100 % de los estratos Alta y Media 1-400, control de sensibilidad ampliado sobre el estrato de baja prioridad (n = 150) y verificación bibliográfica de 59 referencias núcleo contra la base Zotero/CSV— permite trazar cada cifra reportada hasta su origen, y declara explícitamente sus límites (estrato Media revisado hasta el registro 400 de 1824; 14 artículos de Alta pendientes de resolución a texto completo; ver A.13).

## A.13 Limitaciones y consideraciones finales

Aunque el proceso permitió establecer un núcleo bibliográfico relacionado directamente con el desarrollo del dispositivo, se identifican las siguientes limitaciones metodológicas:

1. Los 14 artículos del estrato Alta clasificados inicialmente como "Apoyo" requieren revisión a texto completo para determinar si pueden incorporarse como estudios incluidos o mantenerse como referencias complementarias.

2. El registro de Maray, Alsaif y Tanoon (2022) requiere verificación adicional del DOI debido a que dicho identificador no se encontraba disponible en el registro exportado desde Zotero.

3. El registro de Marino, Migliorelli, Moccia y Sirocchi (2024), *A Monitoring Support System Based on HRNet+DEKR for Neonatal Intensive Care Units*, se incorpora al núcleo (A.7.5) sin DOI verificable en el registro exportado desde Zotero; se recomienda localizarlo manualmente antes de la redacción final del Capítulo 2.

4. Del estrato Media (n = 1824), se realizó revisión manual de los primeros 400 registros priorizados por relevancia temática (21,9 % del estrato). Los 1424 registros restantes no fueron evaluados individualmente debido al alcance del trabajo de grado.

5. La muestra de control del estrato Baja prioridad se amplió de 50 a 150 registros (3,6 % del estrato), lo que permitió identificar posibles estudios relevantes no detectados por el filtro automático y confirmar la estabilidad de la tasa de relación potencial (22 % → 28,0 %); sin embargo, este resultado no debe interpretarse como una estimación poblacional exacta del total de registros no revisados (3981 registros restantes del estrato Baja).

Estas limitaciones no afectan la trazabilidad del núcleo seleccionado, dado que los 59 estudios incluidos en la síntesis cualitativa fueron revisados individualmente y verificados frente a la base bibliográfica consolidada (`base_prisma_sin_duplicados.csv`).

# ANEXO A. REVISIÓN ESTRUCTURADA DE LITERATURA BAJO LINEAMIENTOS PRISMA 2020

## A.1 Introducción

Con el propósito de fundamentar el desarrollo del dispositivo
tecnológico propuesto, se realizó una revisión estructurada de
literatura orientada a identificar avances científicos y tecnológicos
relacionados con la detección auditiva infantil, el análisis de
respuestas conductuales frente a estímulos sonoros, la visión por
computador y la aplicación de aprendizaje automático para clasificación
de patrones observables en bebés.

El proceso de búsqueda, organización y selección documental tomó como
referencia las recomendaciones de PRISMA 2020. La revisión tuvo como
finalidad construir el estado del arte del proyecto, identificando
antecedentes clínicos, tecnológicos y metodológicos relacionados con la
integración de estímulos auditivos, captura audiovisual y análisis
computacional.

------------------------------------------------------------------------

# A.2 Pregunta de revisión

¿Qué avances tecnológicos y metodológicos se han reportado para analizar
respuestas observables frente a estímulos sonoros en población infantil
mediante dispositivos electrónicos, visión por computador y modelos de
aprendizaje automático?

La revisión se organizó en cuatro líneas:

1.  Evaluación auditiva infantil y dispositivos tecnológicos.
2.  Respuestas conductuales frente a estímulos sonoros.
3.  Visión por computador aplicada al análisis infantil.
4.  Aprendizaje automático para clasificación de respuestas observables.

------------------------------------------------------------------------

# A.3 Identificación y gestión bibliográfica

Los registros fueron recopilados desde bases académicas relacionadas con
audiología, ingeniería biomédica, inteligencia artificial y
procesamiento computacional.

Fuentes consultadas:

-   PubMed.
-   Scopus.
-   IEEE Xplore.
-   ScienceDirect.
-   Google Scholar.

La gestión bibliográfica se realizó mediante Zotero.

  Etapa                               Cantidad
  --------------------------------- ----------
  Registros importados                    6567
  Registros duplicados eliminados          573
  Registros únicos para cribado           5994

------------------------------------------------------------------------

# A.4 Estrategia de búsqueda

Las ecuaciones combinaron términos relacionados con población infantil,
audición, comportamiento observable, visión computacional e inteligencia
artificial.

## Audición infantil

("infant" OR "newborn" OR "neonate") AND ("hearing" OR "auditory" OR
"sound stimulus")

## Respuesta conductual

("infant" OR "baby") AND ("auditory stimulus" OR "sound") AND
("behavioral response" OR "movement" OR "orientation")

## Visión computacional

("infant" OR "newborn") AND ("computer vision" OR "facial landmarks" OR
"pose estimation" OR "video analysis")

## Inteligencia artificial

("machine learning" OR "deep learning" OR "artificial intelligence") AND
("infant" OR "neonatal") AND ("classification" OR "detection")

------------------------------------------------------------------------

# A.5 Criterios de selección

## Inclusión

Se incluyeron estudios que:

-   involucraran bebés, neonatos o población infantil;
-   analizaran estímulos auditivos o respuestas observables;
-   utilizaran dispositivos, sensores, visión computacional o
    inteligencia artificial;
-   aportaran metodologías aplicables al análisis automático del
    comportamiento infantil.

## Exclusión

Se excluyeron estudios que:

-   no correspondieran a población infantil;
-   utilizaran IA sin relación con salud o comportamiento infantil;
-   evaluaran estímulos diferentes sin aplicabilidad al proyecto;
-   no presentaran información metodológica suficiente.

------------------------------------------------------------------------

# A.6 Cribado y priorización

Sobre los 5994 registros únicos se realizó un análisis inicial mediante
título, resumen y metadatos.

  Clasificación               Cantidad
  ------------------------- ----------
  Incluir preliminarmente         1368
  Revisar manualmente             3602
  Excluir preliminarmente         1024

Posteriormente se priorizaron los documentos según su relación con:

-   estímulos auditivos;
-   captura audiovisual;
-   análisis facial y cefálico;
-   extracción de características;
-   aprendizaje automático;
-   dispositivos biomédicos.

Resultado:

  Prioridad                                Cantidad
  -------------------------------------- ----------
  Alta relación con la tesis                     39
  Relación metodológica complementaria         1824
  Baja relación temática                       4131

------------------------------------------------------------------------

# A.7 Estudios incluidos en la revisión de literatura

## Evaluación auditiva infantil

  Referencia               Aporte
  ------------------------ -----------------------------------------------
  WHO                      Contexto mundial de pérdida auditiva infantil
  JCIH (2019)              Detección temprana e intervención auditiva
  Norton et al. (2000)     Emisiones otoacústicas en neonatos
  Chan et al. (2022)       Dispositivo portátil basado en OAE
  Yoshinaga-Itano (2003)   Importancia de intervención temprana
  Thornton et al. (2003)   Factores asociados a pruebas OAE
  Stuart y Yang (2001)     Respuestas ABR neonatales
  Bower et al. (2023)      Recomendaciones posteriores al tamizaje

## Respuesta conductual frente al sonido

  -----------------------------------------------------------------------
  Referencia                          Aporte
  ----------------------------------- -----------------------------------
  Ferronato et al. (2014)             Relación entre sonido y movimiento
                                      infantil

  Kezuka et al. (2017)                Orientación hacia fuentes sonoras

  Hicks et al. (2000)                 Evaluación conductual auditiva
  -----------------------------------------------------------------------

## Visión computacional infantil

  -----------------------------------------------------------------------
  Referencia                          Aporte
  ----------------------------------- -----------------------------------
  Adde et al. (2009)                  Análisis computacional de
                                      movimiento

  Stahl et al. (2012)                 Seguimiento mediante flujo óptico

  Støen et al. (2017)                 Identificación automática de
                                      movimientos

  Leo et al. (2022)                   Estado del arte en análisis de
                                      movimiento infantil

  Hashemi et al. (2014)               Visión computacional infantil

  Automatic pose estimation in        Estimación de pose neonatal
  newborn infants (2026)              

  Neonatal Face and Facial Landmark   Detección facial neonatal
  Detection (2023)                    

  Automatic quantitative intelligent  Seguimiento automático mediante
  assessment of neonatal movements    video
  (2024)                              

  Head Orientation Estimation of      Orientación cefálica infantil
  Infants using Deep Learning (2023)  
  -----------------------------------------------------------------------

## Inteligencia artificial aplicada

  -----------------------------------------------------------------------
  Referencia                          Aporte
  ----------------------------------- -----------------------------------
  Blankenship et al. (2026)           Clasificación automática de
                                      reacciones al sonido

  Kulvicius et al. (2025)             Clasificación automática de
                                      movimientos infantiles
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# A.8 Referencias de soporte metodológico

Estas referencias sustentan la implementación del dispositivo:

  Referencia                Uso
  ------------------------- -------------------------
  Breiman (2001)            Algoritmo Random Forest
  Pedregosa et al. (2011)   Scikit-learn
  Lugaresi et al. (2019)    MediaPipe
  Kartynnik et al. (2019)   Geometría facial
  OpenCV                    Procesamiento visual
  Brooke (1996)             Escala SUS
  Davis (1989)              Modelo TAM

------------------------------------------------------------------------

# A.9 Síntesis de la revisión

La literatura evidencia avances en evaluación auditiva, análisis
computacional del comportamiento infantil y aprendizaje automático. Sin
embargo, estos enfoques generalmente se desarrollan de forma
independiente.

La brecha identificada corresponde a la limitada integración de:

-   generación controlada de estímulos sonoros;
-   captura audiovisual del comportamiento infantil;
-   extracción automática de características;
-   clasificación mediante aprendizaje automático;
-   almacenamiento estructurado de ensayos;
-   revisión profesional posterior.

Esta integración constituye el aporte tecnológico del dispositivo
desarrollado.

------------------------------------------------------------------------

# A.10 Flujo PRISMA resumido

Registros identificados:

n = 6567

↓

Duplicados eliminados:

n = 573

↓

Registros únicos evaluados:

n = 5994

↓

Artículos priorizados:

n = 39

↓

Estudios incluidos en síntesis cualitativa:

24 estudios científicos principales

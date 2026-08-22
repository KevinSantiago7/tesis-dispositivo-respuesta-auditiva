# ANEXO A. REVISIÓN ESTRUCTURADA DE LITERATURA BAJO LINEAMIENTOS PRISMA 2020

## Versión final consolidada para GitHub

## A.1 Introducción

Se realizó una revisión estructurada de literatura con el propósito de fundamentar el desarrollo del dispositivo tecnológico propuesto, identificando avances relacionados con evaluación auditiva infantil, respuestas conductuales frente a estímulos sonoros, visión por computador y aprendizaje automático.

El proceso tomó como referencia las recomendaciones PRISMA 2020. Debido al volumen documental recuperado, se aplicó una estrategia de dos fases:

1. Priorización automatizada por concordancia temática.
2. Verificación manual de los registros con mayor relevancia.

La revisión tuvo como finalidad construir el estado del arte del proyecto, identificando antecedentes clínicos, tecnológicos y metodológicos relacionados con la integración de estímulos auditivos, captura audiovisual y análisis computacional.

Esta revisión corresponde a una revisión estructurada orientada al desarrollo tecnológico del dispositivo y no a una revisión sistemática clínica con metaanálisis.

---

# A.2 Pregunta de revisión

¿Qué avances tecnológicos y metodológicos se han reportado para analizar respuestas observables frente a estímulos sonoros en población infantil mediante dispositivos electrónicos, visión por computador y modelos de aprendizaje automático?

La revisión se organizó en cuatro líneas:

1. Evaluación auditiva infantil y dispositivos tecnológicos.
2. Respuestas conductuales frente a estímulos sonoros.
3. Visión computacional aplicada al análisis infantil.
4. Aprendizaje automático para clasificación de patrones observables.

---

# A.3 Bases bibliográficas y gestión documental

La búsqueda bibliográfica se realizó en bases académicas relacionadas con ciencias biomédicas, ingeniería e inteligencia artificial.

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

# A.4 Estrategia de búsqueda

Las estrategias combinaron términos relacionados con población infantil, audición, respuesta conductual, visión computacional, inteligencia artificial y dispositivos biomédicos.

## A.4.1 Audición infantil

```text
("infant" OR "newborn" OR "neonate" OR "baby")
AND
("hearing" OR "auditory" OR "sound stimulus" OR "auditory response")
AND
("screening" OR "assessment" OR "detection")
```

## A.4.2 Respuesta conductual

```text
("infant" OR "newborn" OR "baby")
AND
("auditory stimulus" OR "sound")
AND
("behavioral response" OR "movement" OR "orientation" OR "reaction")
```

## A.4.3 Visión computacional

```text
("infant" OR "newborn" OR "neonate")
AND
("computer vision" OR "facial landmarks"
OR "pose estimation"
OR "video analysis"
OR "motion tracking")
AND
("behavior analysis" OR "movement analysis")
```

## A.4.4 Inteligencia artificial y aprendizaje automático

```text
("machine learning"
OR "deep learning"
OR "artificial intelligence")
AND
("infant"
OR "newborn"
OR "neonatal")
AND
("classification"
OR "detection"
OR "recognition"
OR "prediction")
```

## A.4.5 Dispositivos tecnológicos

```text
("infant" OR "newborn")
AND
("device"
OR "sensor"
OR "embedded system"
OR "portable")
AND
("machine learning"
OR "computer vision"
OR "artificial intelligence")
```

---

# A.5 Criterios de selección

## Inclusión

Se incluyeron estudios que:

- involucraran bebés, neonatos o población infantil;
- analizaran estímulos auditivos o respuestas observables;
- utilizaran dispositivos, sensores, visión computacional o inteligencia artificial;
- aportaran metodologías aplicables al análisis automático del comportamiento infantil;
- describieran extracción de características o clasificación automática.

## Exclusión

Se excluyeron estudios:

- realizados exclusivamente en adultos;
- basados en modelos animales;
- sin relación con comportamiento infantil o audición;
- con inteligencia artificial sin aplicación relacionada;
- sin información metodológica suficiente.

---

# A.6 Proceso de cribado

Los 5994 registros únicos fueron priorizados mediante coincidencias de título, resumen y palabras clave con términos asociados a:

- población infantil;
- audición;
- comportamiento observable;
- visión computacional;
- inteligencia artificial;
- dispositivos biomédicos.

## Priorización inicial

| Estrato | Cantidad |
|---|---:|
| Alta relación | 39 |
| Media relación | 1824 |
| Baja relación | 4131 |

Esta etapa correspondió a una priorización temática y no a una decisión definitiva de inclusión o exclusión.

---

# A.6.1 Verificación manual

Los registros priorizados fueron evaluados mediante revisión de título, resumen y pertinencia con los objetivos del proyecto.

| Categoría | Descripción |
|---|---|
| Núcleo | Relación directa con los componentes principales del dispositivo |
| Apoyo directo | Metodologías transferibles al desarrollo tecnológico |
| Contexto | Fundamentación clínica o tecnológica |
| Excluir | Sin relación suficiente |

## Resultados

| Origen | Núcleo | Apoyo directo | Contexto | Excluir |
|---|---:|---:|---:|---:|
| Alta (39) | 20 | 9 | 4 | 6 |
| Media 1-30 (30) | 6 | 9 | 5 | 10 |
| Media 31-200 (170) | 25 | 45 | 53 | 47 |

---

# A.7 Síntesis del estado del arte

Los estudios seleccionados se organizaron en cuatro líneas:

## Evaluación auditiva infantil

Incluye:

- emisiones otoacústicas;
- potenciales evocados auditivos;
- tamizaje neonatal;
- dispositivos portátiles.

## Respuesta conductual frente al sonido

Incluye:

- orientación hacia estímulos sonoros;
- movimientos observables;
- respuestas faciales y motoras.

## Visión computacional infantil

Incluye:

- detección facial;
- landmarks;
- estimación de pose;
- seguimiento de movimiento.

## Inteligencia artificial

Incluye:

- extracción automática de características;
- clasificación supervisada;
- modelos predictivos.

---

# A.8 Brecha tecnológica

La literatura evidencia avances individuales en tamizaje auditivo, análisis de movimiento infantil, visión computacional y modelos de inteligencia artificial.

Sin embargo, estos enfoques generalmente se desarrollan de forma independiente.

La brecha identificada corresponde a la integración de:

- generación controlada de estímulos sonoros;
- captura audiovisual del comportamiento infantil;
- extracción automática de características;
- clasificación mediante aprendizaje automático;
- almacenamiento estructurado de ensayos;
- revisión profesional posterior.

Esta integración constituye el aporte tecnológico del dispositivo desarrollado.

---

# A.9 Flujo PRISMA resumido

```text
Registros identificados:
n = 6567

Duplicados eliminados:
n = 573

Registros únicos:
n = 5994

Priorización temática:
Alta = 39
Media = 1824
Baja = 4131

Evaluación manual:
Alta = 39
Media = 200

Muestra de control Baja:
n = 50

Estudios seleccionados para síntesis:
n = 51
```

---

# A.10 Consideración final

La presente revisión permitió identificar antecedentes científicos y tecnológicos que sustentan la arquitectura del dispositivo desarrollado, especialmente en la integración de estímulos auditivos, captura audiovisual, extracción de características y clasificación automática de respuestas observables en población infantil.

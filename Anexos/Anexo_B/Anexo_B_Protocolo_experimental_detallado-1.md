# ANEXO B. PROTOCOLO EXPERIMENTAL DETALLADO

------------------------------------------------------------------------

# B.1 Objetivo del protocolo

Este anexo presenta la versión ampliada y operativa del protocolo
experimental descrito en la sección **3.7 Protocolo experimental y
adquisición de datos**, con el nivel de detalle necesario para
comprender la ejecución de las sesiones, la adquisición de registros
audiovisuales y la organización de la información obtenida durante los
ensayos.

El procedimiento descrito no corresponde a una prueba audiológica ni a
un procedimiento clínico. Corresponde a una estrategia experimental
orientada a presentar estímulos sonoros controlados y registrar mediante
medios audiovisuales manifestaciones conductuales observables en bebés
de 0 a 6 meses, dentro del alcance tecnológico del dispositivo
desarrollado.

------------------------------------------------------------------------

# B.2 Equipo y materiales utilizados

  -----------------------------------------------------------------------
  Componente              Descripción             Función dentro del
                                                  protocolo
  ----------------------- ----------------------- -----------------------
  Jetson Nano (2 GB)      Unidad de procesamiento Ejecuta procesos
                          embebida utilizada para asociados con la
                          ejecutar procesos       interfaz de control,
                          computacionales         procesamiento
                          asociados al            audiovisual, extracción
                          dispositivo.            de características y
                                                  comunicación con
                                                  módulos desarrollados.

  Teléfono móvil          Dispositivo utilizado   Captura el
                          como sistema de         comportamiento
                          adquisición audiovisual observable del bebé
                          mediante cámara IP.     durante la calibración
                                                  y ensayos
                                                  experimentales.

  Parlante externo        Dispositivo encargado   Genera los estímulos
                          de reproducir estímulos utilizados durante cada
                          sonoros configurados.   ensayo.

  Estructura impresa en   Soporte físico diseñado Permite organizar
  3D                      para integrar           físicamente los
                          componentes principales elementos del sistema.
                          del prototipo.          

  Batería o fuente        Sistema de suministro   Permite la operación
  portátil                energético.             durante las sesiones
                                                  experimentales.
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# B.3 Roles y responsabilidades durante la sesión experimental

  -----------------------------------------------------------------------
  Rol                                 Responsabilidad
  ----------------------------------- -----------------------------------
  Bebé participante                   Participante del estudio cuya
                                      respuesta conductual observable fue
                                      registrada mediante adquisición
                                      audiovisual. Su bienestar fue
                                      prioritario durante todo el
                                      procedimiento.

  Padre, madre o cuidador             Autoriza la participación, acompaña
                                      al bebé y puede solicitar pausas o
                                      finalización del procedimiento.

  Operador del dispositivo            Configura la sesión, verifica
                                      condiciones técnicas, ejecuta
                                      calibración e inicia y finaliza los
                                      ensayos.

  Apoyo experimental                  Colabora con preparación del
                                      espacio y observación del
                                      procedimiento.

  Profesional del área auditiva       Participa en la revisión
                                      experimental y valoración de la
                                      información generada.
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# B.4 Condiciones del entorno experimental

Antes de cada sesión se verificaron:

-   Iluminación suficiente para permitir la detección facial.
-   Reducción razonable del ruido ambiental.
-   Espacio seguro y cómodo para el bebé.
-   Estabilidad de la cámara.
-   Visibilidad del rostro y parte superior del cuerpo.

Estas condiciones fueron verificadas cualitativamente por el equipo
investigador y se consideraron condiciones contextuales del
procedimiento experimental.

------------------------------------------------------------------------

# B.5 Configuración previa a la sesión

1.  Se explicó el procedimiento al representante legal y se diligenció
    el consentimiento informado.
2.  Se asignó un código interno al participante para mantener la
    trazabilidad.
3.  Se creó la sesión experimental dentro de la plataforma.
4.  Se realizó el montaje físico del dispositivo.
5.  Se verificaron cámara, comunicación entre componentes, encuadre e
    iluminación.
6.  Se realizaron ajustes necesarios en la posición del bebé o del
    sistema.

------------------------------------------------------------------------

# B.6 Procedimiento paso a paso por ensayo

Cada ensayo fue considerado la unidad mínima de adquisición
experimental, debido a que relacionó un estímulo específico con un
registro audiovisual y los datos generados durante el procesamiento
posterior.

## 1. Calibración inicial

Con el bebé ubicado frente al dispositivo y con el rostro visible, se
estableció una referencia inicial del estado previo al estímulo,
incluyendo orientación cefálica y condiciones faciales.

## 2. Configuración del ensayo

Se definieron los parámetros del estímulo:

-   Tipo de estímulo.
-   Frecuencia cuando aplicaba.
-   Duración.
-   Lateralidad.
-   Nivel relativo de salida.

## 3. Inicio del registro audiovisual

Se inició la captura del comportamiento observable del bebé durante el
ensayo.

## 4. Presentación del estímulo

Se reprodujo el estímulo configurado manteniendo la adquisición
audiovisual para conservar la relación temporal entre estímulo y
comportamiento observado.

## 5. Observación posterior

Se continuó el registro audiovisual para analizar modificaciones
respecto al estado inicial del participante.

## 6. Finalización y almacenamiento

Cada ensayo fue asociado con:

-   Código del participante.
-   Sesión experimental.
-   Identificador del ensayo.
-   Parámetros del estímulo.
-   Registro audiovisual.

------------------------------------------------------------------------

# B.7 Parámetros de estimulación utilizados

  -----------------------------------------------------------------------
  Parámetro               Valores utilizados      Observación
  ----------------------- ----------------------- -----------------------
  Tipo de estímulo        Tono sintético / sonido Seleccionado según
                          pregrabado              configuración del
                                                  ensayo.

  Frecuencia              500 Hz, 1000 Hz, 2000   Aplicable para
                          Hz y 4000 Hz            estímulos tonales.

  Nivel relativo de       0,3; 0,5 y 0,7          Parámetro interno. No
  salida                                          corresponde a una
                                                  medición calibrada en
                                                  dB SPL.

  Lateralidad             Izquierda / derecha     Define la ubicación
                                                  relativa del estímulo.

  Duración del estímulo   1 segundo               Tiempo de reproducción
                                                  utilizado.
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# B.8 Estructura temporal del ensayo

``` text
Segundo 0              Segundo 3          Segundo 4                  Segundo 8

|------ Línea base ------|--- Estímulo ---|------ Observación posterior ------|

       3 segundos             1 segundo              4 segundos
```

Cada ensayo estuvo conformado por:

-   **Línea base:** periodo previo utilizado para establecer una
    referencia inicial.
-   **Presentación del estímulo:** intervalo de reproducción del sonido
    configurado.
-   **Observación posterior:** periodo posterior utilizado para analizar
    cambios respecto al estado inicial.

------------------------------------------------------------------------

# B.9 Criterios de interrupción y pausa

La sesión podía pausarse o finalizar ante:

-   Llanto persistente.
-   Fatiga o incomodidad.
-   Pérdida prolongada de atención.
-   Solicitud del cuidador.

El bienestar del bebé tuvo prioridad sobre la obtención de registros
experimentales.

------------------------------------------------------------------------

# B.10 Criterios de aceptación y descarte de ensayos

Los registros utilizados debían conservar:

  -----------------------------------------------------------------------
  Criterio                            Descripción
  ----------------------------------- -----------------------------------
  Visibilidad facial adecuada         Permitir detección facial y
                                      extracción de características.

  Iluminación suficiente              Mantener condiciones adecuadas para
                                      procesamiento.

  Continuidad del video               Conservar la secuencia completa del
                                      ensayo.

  Línea base disponible               Permitir comparación con el estado
                                      inicial.

  Disponibilidad de características   Permitir extracción de variables
                                      del modelo.

  Correspondencia experimental        Mantener relación entre estímulo y
                                      registro.
  -----------------------------------------------------------------------

Se excluyeron registros con:

-   Obstrucción facial.
-   Fallas de adquisición audiovisual.
-   Pérdida prolongada del seguimiento facial.
-   Ausencia de información asociada al estímulo.
-   Condiciones externas que limitaran el análisis computacional.

------------------------------------------------------------------------

# B.11 Organización y trazabilidad de los datos

Cada ensayo fue almacenado mediante una estructura jerárquica:

  Elemento               Información asociada
  ---------------------- --------------------------------------------
  Participante           Código interno asignado al bebé.
  Sesión                 Jornada experimental correspondiente.
  Ensayo                 Registro individual del experimento.
  Estímulo               Parámetros configurados.
  Registro audiovisual   Video obtenido durante el ensayo.
  Características        Variables extraídas durante procesamiento.
  Modelo                 Clasificación operacional generada.

La trazabilidad fue organizada mediante:

**Participante → Sesión → Ensayo → Estímulo → Registro audiovisual →
Características → Modelo → Resultado operacional**

La descripción detallada de variables se presenta en el **Anexo E.
Diccionario del dataset**, mientras que los resultados del modelo se
presentan en el **Anexo H. Resultados detallados del modelo Random
Forest**.

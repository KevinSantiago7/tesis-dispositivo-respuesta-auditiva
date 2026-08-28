# ANEXO I

# Cálculo detallado de los indicadores de concordancia IA-especialista

Este anexo presenta el desarrollo completo del cálculo de los
indicadores de concordancia entre la clasificación generada por el
modelo **Random Forest** y la valoración del especialista, referidos en
la sección **5.4.5**.

Los cálculos se realizan a partir de la matriz de confusión obtenida:

  Clasificación                      Valor
  -------------------------------- -------
  Verdaderos positivos (VP)             23
  Falsos positivos (FP)                  4
  Falsos negativos (FN)                 11
  Verdaderos negativos (VN)             15
  Total de ensayos evaluados (N)        53

------------------------------------------------------------------------

# 1. Concordancia global

La concordancia global representa la proporción de ensayos donde la
clasificación del modelo coincide con la valoración del especialista.

### Fórmula

$$
Concordancia = \frac{VP + VN}{N}
$$

### Sustitución

$$
Concordancia = \frac{23+15}{53}
$$

$$
Concordancia = \frac{38}{53}=0,7169
$$

### Resultado

## Concordancia global = **71,7 %**

El modelo coincidió con la valoración del especialista en **38 de los 53
ensayos evaluados**.

------------------------------------------------------------------------

# 2. Discrepancia

La discrepancia representa los casos donde la clasificación del modelo
no coincide con la valoración del especialista.

### Fórmula

$$
Discrepancia=\frac{FP+FN}{N}
$$

### Sustitución

$$
Discrepancia=\frac{4+11}{53}
$$

$$
Discrepancia=0,283
$$

### Resultado

## Discrepancia = **28,3 %**

------------------------------------------------------------------------

# 3. Sensibilidad

La sensibilidad indica la capacidad del modelo para identificar
correctamente los ensayos donde el especialista determinó una respuesta
conductual observable.

### Fórmula

$$
Sensibilidad=\frac{VP}{VP+FN}
$$

### Sustitución

$$
Sensibilidad=\frac{23}{23+11}
$$

$$
Sensibilidad=\frac{23}{34}=0,676
$$

### Resultado

## Sensibilidad = **67,6 %**

El sistema identificó correctamente el **67,6 % de las respuestas
conductuales observables confirmadas por el especialista**.

------------------------------------------------------------------------

# 4. Especificidad

La especificidad representa la capacidad del modelo para identificar
correctamente los ensayos donde no se observó respuesta.

### Fórmula

$$
Especificidad=\frac{VN}{VN+FP}
$$

### Sustitución

$$
Especificidad=\frac{15}{15+4}
$$

$$
Especificidad=\frac{15}{19}=0,789
$$

### Resultado

## Especificidad = **78,9 %**

------------------------------------------------------------------------

# 5. Precisión

La precisión indica qué proporción de las clasificaciones positivas
realizadas por el modelo fueron confirmadas por el especialista.

### Fórmula

$$
Precisión=\frac{VP}{VP+FP}
$$

### Sustitución

$$
Precisión=\frac{23}{23+4}
$$

$$
Precisión=\frac{23}{27}=0,852
$$

### Resultado

## Precisión = **85,2 %**

Cuando el modelo clasificó un ensayo como respuesta conductual
observable, coincidió con el especialista en el **85,2 % de los casos**.

------------------------------------------------------------------------

# 6. Valor predictivo negativo (VPN)

### Fórmula

$$
VPN=\frac{VN}{VN+FN}
$$

### Sustitución

$$
VPN=\frac{15}{15+11}
$$

$$
VPN=0,577
$$

### Resultado

## VPN = **57,7 %**

Cuando el modelo indicó respuesta no concluyente, coincidió con la
ausencia de reacción observada por el especialista en el **57,7 % de los
casos**.

------------------------------------------------------------------------

# 7. F1-score

El F1-score combina la precisión y la sensibilidad en un único
indicador.

### Fórmula

$$
F1=2\times\frac{Precisión \times Sensibilidad}{Precisión+Sensibilidad}
$$

### Sustitución

$$
F1=2\times\frac{0,852\times0,676}{0,852+0,676}
$$

$$
F1=0,754
$$

### Resultado

## F1-score = **75,4 %**

------------------------------------------------------------------------

# 8. Kappa de Cohen

El coeficiente Kappa de Cohen permite determinar el nivel de
concordancia entre dos evaluadores considerando el acuerdo esperado por
azar.

## 8.1 Acuerdo observado

$$
P_o=\frac{VP+VN}{N}
$$

$$
P_o=\frac{38}{53}=0,717
$$

------------------------------------------------------------------------

## 8.2 Proporciones marginales del especialista

**Reaccionó**

$$
\frac{23+11}{53}=0,642
$$

**No reaccionó**

$$
\frac{4+15}{53}=0,358
$$

------------------------------------------------------------------------

## 8.3 Proporciones marginales del modelo

**Respuesta observable**

$$
\frac{23+4}{53}=0,509
$$

**No concluyente**

$$
\frac{11+15}{53}=0,491
$$

------------------------------------------------------------------------

## 8.4 Acuerdo esperado por azar

$$
P_e=(0,509\times0,642)+(0,491\times0,358)
$$

$$
P_e=0,504
$$

------------------------------------------------------------------------

## 8.5 Cálculo de Kappa

### Fórmula

$$
K=\frac{P_o-P_e}{1-P_e}
$$

### Sustitución

$$
K=\frac{0,717-0,504}{1-0,504}
$$

$$
K=0,429
$$

### Resultado

# Kappa de Cohen = **0,43**

------------------------------------------------------------------------

# Interpretación del coeficiente Kappa

  Rango de Kappa   Interpretación
  ---------------- ----------------
  0 -- 0,20        Leve
  0,21 -- 0,40     Aceptable
  0,41 -- 0,60     Moderada
  0,61 -- 0,80     Sustancial

**Tabla. Escala de interpretación de Kappa de Cohen (Landis y Koch,
1977).**

Con un valor de **K = 0,43**, el resultado corresponde a una
**concordancia moderada** entre la clasificación computacional generada
por el modelo y la valoración realizada por el especialista.

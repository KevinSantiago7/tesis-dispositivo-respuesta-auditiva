# ANEXO I: Cálculo detallado de los indicadores de concordancia IA-especialista

Este anexo presenta el desarrollo completo del cálculo de los indicadores de concordancia entre la clasificación generada por el modelo Random Forest y la valoración del especialista, referidos en la sección 5.4.5, a partir de la matriz de confusión con VP = 23, FP = 4, FN = 11 y VN = 15 (n = 53 ensayos).

---

## 1. Concordancia global

Concordancia = (VP + VN) / N

Concordancia = (23 + 15) / 53 = 38 / 53 = 0,7169

**Concordancia global = 71,7 %**

El modelo coincidió con la valoración del especialista en 38 de los 53 ensayos evaluados.

---

## 2. Discrepancia

Discrepancia = (FP + FN) / N

Discrepancia = (4 + 11) / 53 = 0,283

**Discrepancia = 28,3 %**

---

## 3. Sensibilidad

Sensibilidad = VP / (VP + FN)

Sensibilidad = 23 / (23 + 11) = 23 / 34 = 0,676

**Sensibilidad = 67,6 %**

El sistema identificó correctamente el 67,6 % de las respuestas conductuales observables confirmadas por el especialista.

---

## 4. Especificidad

Especificidad = VN / (VN + FP)

Especificidad = 15 / (15 + 4) = 15 / 19 = 0,789

**Especificidad = 78,9 %**

El sistema identificó correctamente el 78,9 % de los ensayos donde el especialista no observó respuesta.

---

## 5. Precisión

Precisión = VP / (VP + FP)

Precisión = 23 / (23 + 4) = 23 / 27 = 0,852

**Precisión = 85,2 %**

Cuando el modelo clasificó un ensayo como respuesta conductual observable, coincidió con el especialista en el 85,2 % de los casos.

---

## 6. Valor predictivo negativo (VPN)

VPN = VN / (VN + FN)

VPN = 15 / (15 + 11) = 0,577

**VPN = 57,7 %**

Cuando el modelo indicó respuesta no concluyente, coincidió con la ausencia de reacción observada por el especialista en el 57,7 % de los casos.

---

## 7. F1-score

F1 = 2 × (Precisión × Sensibilidad) / (Precisión + Sensibilidad)

F1 = 2 × (0,852 × 0,676) / (0,852 + 0,676) = 0,754

**F1-score = 75,4 %**

---

## 8. Kappa de Cohen

El coeficiente Kappa de Cohen permite evaluar el nivel de concordancia entre dos evaluadores considerando el acuerdo esperado por azar.

### Acuerdo observado

Po = (VP + VN) / N

Po = 38 / 53 = 0,717

### Proporciones marginales del especialista

Reaccionó = (23 + 11) / 53 = 0,642

No reaccionó = (4 + 15) / 53 = 0,358

### Proporciones marginales del modelo

Respuesta observable = (23 + 4) / 53 = 0,509

No concluyente = (11 + 15) / 53 = 0,491

### Acuerdo esperado por azar

Pe = (0,509 × 0,642) + (0,491 × 0,358) = 0,504

### Cálculo de Kappa

K = (Po − Pe) / (1 − Pe)

K = (0,717 − 0,504) / (1 − 0,504) = 0,429

**Kappa de Cohen = 0,43**

---

## Interpretación del coeficiente Kappa

| Rango de Kappa | Interpretación |
|---|---|
| 0 – 0,20 | Leve |
| 0,21 – 0,40 | Aceptable |
| 0,41 – 0,60 | Moderada |
| 0,61 – 0,80 | Sustancial |

**Tabla. Escala de interpretación de Kappa de Cohen (Landis y Koch, 1977).**

Con un valor de **K = 0,43**, el resultado obtenido corresponde a una **concordancia moderada** entre la clasificación computacional generada por el modelo y la valoración realizada por el especialista.

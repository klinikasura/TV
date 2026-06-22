from sklearn.linear_model import LinearRegression
import numpy as np

# Data contoh (luas rumah vs harga)
luas = np.array([[50], [60], [70], [80], [90]])
harga = np.array([150, 180, 210, 240, 270])

# Membuat model
model = LinearRegression()
model.fit(luas, harga)

# Prediksi harga rumah ukuran 100
prediksi = model.predict([[100]])

print("Prediksi harga rumah:", prediksi[0])

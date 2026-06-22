from sklearn.linear_model import LinearRegression
import numpy as np

# data (jam belajar vs nilai)
X = np.array([[1], [2], [3], [4], [5]])
y = np.array([50, 60, 70, 80, 90])

# buat model AI
model = LinearRegression()
model.fit(X, y)

# prediksi
prediksi = model.predict([[6]])

print("Prediksi nilai untuk 6 jam belajar:", prediksi[0])

# Animal Image Classifier

This application uses Node.js for the API server and Python with PyTorch for image classification. It can identify animal types, breeds, and categories from uploaded images.

## Features

- Identify animal species and breeds
- Classify animals into categories (Dog, Cat, Bird, etc.)
- Provide confidence scores for predictions
- Simple web interface for uploading and testing

## System Requirements

### Node.js
- Node.js v14.0.0 or higher
- npm or yarn

### Python
- Python 3.8 or higher
- PyTorch
- Torchvision
- Pillow (PIL)

## Installation

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/animal-image-classifier.git
cd animal-image-classifier
```

### 2. Install Node.js dependencies
```bash
npm install
```

### 3. Install Python dependencies
```bash
pip install torch torchvision pillow
```

### 4. Create required directories
```bash
mkdir uploads
mkdir public
```

### 5. Create a basic HTML interface (see the frontend code below)

## Running the Application

1. Start the server:
```bash
npm start
```

2. Access the web interface at http://localhost:3000

## How It Works

1. The user uploads an image through the web interface
2. The Node.js server receives and validates the upload
3. The image is saved to the `/uploads` directory
4. The Node.js server calls the Python script with the image path
5. The Python script:
   - Loads a pre-trained ResNet50 model
   - Processes the image for classification
   - Makes predictions about the animal type and breed
   - Returns results as JSON
6. The Node.js server returns the classifications to the frontend
7. Results are displayed to the user

## API Endpoints

### `POST /classify`
Accepts image uploads and returns classification results

**Request:**
- Form data with an image file (key: `image`)

**Response:**
```json
{
  "success": true,
  "animal_type": "Dog",
  "predictions": [
    {
      "breed": "Golden Retriever",
      "category": "Dog",
      "confidence": 95
    },
    {
      "breed": "Labrador Retriever",
      "category": "Dog",
      "confidence": 85
    },
    ...
  ]
}
```

## Limitations

- The classifier is limited to animals in the ImageNet dataset
- Accuracy depends on image quality and animal visibility
- No fine-tuning for specific animal breeds has been performed

## Extending the Project

1. Fine-tune the model for better animal classification
2. Add more detailed breed information
3. Implement a database to store classification history
4. Create a more advanced frontend with previous results
5. Add authentication for API access

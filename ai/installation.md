# Installation Guide

This guide provides detailed instructions for setting up the Animal Image Classifier on different operating systems.

## Prerequisites

### Common Requirements
- Git (for cloning the repository)
- Web browser (Chrome, Firefox, Safari, Edge)

### Node.js Environment
- Node.js v14.0.0 or higher
- npm (included with Node.js) or yarn

### Python Environment
- Python 3.8 or higher
- pip (Python package manager)
- Virtual environment (recommended)

## Installation Steps

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/animal-image-classifier.git
cd animal-image-classifier
```

### Step 2: Set Up Node.js Environment

#### Install Node.js Dependencies

```bash
npm install
```

This will install:
- Express (web server)
- Multer (file upload handling)
- CORS (Cross-Origin Resource Sharing)
- Other dependencies specified in package.json

### Step 3: Set Up Python Environment

#### Option A: Using Virtual Environment (Recommended)

**For Windows:**
```bash
# Create a virtual environment
python -m venv venv

# Activate the virtual environment
venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt
```

**For macOS/Linux:**
```bash
# Create a virtual environment
python3 -m venv venv

# Activate the virtual environment
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt
```

#### Option B: Global Installation (Not Recommended)

```bash
pip install -r requirements.txt
```

### Step 4: Create Required Directories

```bash
mkdir -p uploads public
```

### Step 5: Copy the HTML Frontend

Copy the contents of the provided `index.html` file to `public/index.html`.

## Starting the Application

1. Make sure your Python virtual environment is activated (if using one)
2. Start the Node.js server:

```bash
npm start
```

3. Access the application at: http://localhost:3000

## Troubleshooting

### Common Issues and Solutions

#### Issue: "Error: Cannot find module 'express'"
**Solution**: Make sure you've run `npm install` in the project directory.

#### Issue: "Error: Cannot find module 'torch'"
**Solution**: Make sure you've installed PyTorch with `pip install torch torchvision`.

#### Issue: "Error: ENOENT: no such file or directory, mkdir 'uploads'"
**Solution**: Create the `uploads` directory manually: `mkdir uploads`.

#### Issue: Images not being classified correctly
**Solution**: Ensure:
1. The image is clear and shows the animal clearly
2. The file is in a common image format (JPEG, PNG)
3. PyTorch and dependencies are installed correctly

#### Issue: "Error: spawn python ENOENT"
**Solution**: Ensure Python is in your system PATH or specify the full path to the Python executable in app.js.

## Platform-Specific Notes

### Windows
- If you get an error about Python not being found, make sure Python is in your PATH environment variable or update the spawn command in app.js with the full path to python.exe.

### macOS
- Make sure you have Xcode Command Line Tools installed for PyTorch.

### Linux
- You might need to install additional libraries for image processing: `sudo apt-get install python3-pil python3-pil.imagetk` (for Ubuntu/Debian).

## Setting up a Development Environment

For development purposes, you can use nodemon to automatically restart the server when files change:

```bash
npm install -g nodemon
nodemon app.js
```

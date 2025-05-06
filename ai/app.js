// app.js - Main Node.js API server for animal image classification
const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const { spawn } = require('child_process');
const cors = require('cors');

// Initialize express app
const app = express();
const port = 5000;

app.use(cors());
app.use(express.json());
app.use(express.static('public'));

// Set up multer for handling file uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    const uploadDir = 'uploads';
    if (!fs.existsSync(uploadDir)) {
      fs.mkdirSync(uploadDir);
    }
    cb(null, uploadDir);
  },
  filename: (req, file, cb) => {
    cb(null, `${Date.now()}-${file.originalname}`);
  }
});

const upload = multer({ 
  storage,
  fileFilter: (req, file, cb) => {
    // Accept only image files
    if (file.mimetype.startsWith('image/')) {
      cb(null, true);
    } else {
      cb(new Error('Only image files are allowed!'), false);
    }
  },
  limits: {
    fileSize: 5 * 1024 * 1024, // 5MB max
  }
});

// Simple landing page
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Image preview endpoint
app.get('/image/:filename', (req, res) => {
  const filePath = path.join(__dirname, 'uploads', req.params.filename);
  if (fs.existsSync(filePath)) {
    res.sendFile(filePath);
  }
  else {
    res.status(404).json({ error: 'Image not found' });
  }
});

// Endpoint to handle image upload and classification
app.post('/classify', upload.single('image'), (req, res) => {
  if (!req.file) {
    return res.status(400).json({ error: 'No image uploaded' });
  }

  const imagePath = req.file.path;
  console.log(`Processing image: ${imagePath}`);

  // Call the Python script for classification
  const pythonProcess = spawn('python', ['classifier.py', imagePath]);
  
  let result = '';
  let error = '';

  pythonProcess.stdout.on('data', (data) => {
    result += data.toString();
  });

  pythonProcess.stderr.on('data', (data) => {
    error += data.toString();
    console.error(`Python error: ${data}`);
  });

  pythonProcess.on('close', (code) => {
    console.log(`Python process exited with code ${code}`);
    
    if (code !== 0) {
      return res.status(500).json({ error: 'Classification failed', details: error });
    }

    try {
      const classification = JSON.parse(result);
      classification.imageUrl = `http://localhost:${port}/image/${path.basename(imagePath)}`;
      return res.json(classification);
    } catch (e) {
      console.error('Failed to parse Python output:', e);
      return res.status(500).json({ error: 'Failed to parse classification result', details: result });
    }
  });
});

// Error handling middleware
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: err.message });
});

// Start the server
app.listen(port, () => {
  console.log(`Animal classifier server running at http://localhost:${port}`);
});

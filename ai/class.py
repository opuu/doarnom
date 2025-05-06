#!/usr/bin/env python
# classifier.py - Python script for animal image classification
import sys
import json
import os
import numpy as np
from PIL import Image, ExifTags
import torch
import torchvision.transforms as transforms
import torchvision.models as models

# Check if we have the right number of arguments
if len(sys.argv) != 2:
    print(json.dumps({"error": "Please provide an image path"}))
    sys.exit(1)

image_path = sys.argv[1]

# Function to load class labels
def load_labels():
    # These are simplified animal categories - in a production system,
    # you would load this from a more comprehensive file
    animal_categories = {
        # Mammals
        'n02085620': 'Chihuahua',
        'n02085782': 'Japanese Spaniel',
        'n02085936': 'Maltese',
        'n02086079': 'Pekinese',
        'n02086240': 'Shih-Tzu',
        'n02086646': 'Blenheim Spaniel',
        'n02086910': 'Papillon',
        'n02087046': 'Toy Terrier',
        'n02087394': 'Rhodesian Ridgeback',
        'n02088094': 'Afghan Hound',
        'n02088238': 'Basset Hound',
        'n02088364': 'Beagle',
        'n02088466': 'Bloodhound',
        'n02088632': 'Bluetick',
        'n02089078': 'Black-and-tan Coonhound',
        'n02089867': 'Walker Hound',
        'n02089973': 'English Foxhound',
        'n02093256': 'Staffordshire Bullterrier',
        'n02093428': 'American Staffordshire Terrier',
        'n02094433': 'Yorkshire Terrier',
        'n02099601': 'Golden Retriever',
        'n02099712': 'Labrador Retriever',
        'n02106030': 'Collie',
        'n02106166': 'Border Collie',
        'n02106550': 'Rottweiler',
        'n02106662': 'German Shepherd',
        'n02109047': 'Great Dane',
        'n02123045': 'Tabby Cat',
        'n02123159': 'Persian Cat',
        'n02123394': 'Egyptian Cat',
        'n02123597': 'Siamese Cat',
        'n02124075': 'Egyptian Cat',
        'n02127052': 'Lynx',
        'n02128385': 'Leopard',
        'n02128757': 'Tiger',
        'n02129165': 'Lion',
        'n02129604': 'Tiger',
        'n02130308': 'Cheetah',
        'n02134084': 'Ice Bear',
        'n02134418': 'Sloth Bear',
        'n02480495': 'Orangutan',
        'n02480855': 'Gorilla',
        'n02481823': 'Chimpanzee',

        # Birds
        'n01514668': 'Cock',
        'n01514859': 'Hen',
        'n01530575': 'Brambling',
        'n01531178': 'Goldfinch',
        'n01532829': 'House Finch',
        'n01534433': 'Junco',
        'n01537544': 'Indigo Bunting',
        'n01558993': 'Robin',
        'n01560419': 'Bulbul',
        'n01580077': 'Jay',
        'n01582220': 'Magpie',
        'n01592084': 'Chickadee',
        'n01601694': 'Water Ouzel',
        'n01608432': 'Kite',
        'n01614925': 'Bald Eagle',
        'n01616318': 'Vulture',
        'n01622779': 'Great Grey Owl',

        # Reptiles & Amphibians
        'n01629819': 'European Fire Salamander',
        'n01630670': 'Common Newt',
        'n01631663': 'Eft',
        'n01632458': 'Spotted Salamander',
        'n01632777': 'Axolotl',
        'n01641577': 'Bullfrog',
        'n01644373': 'Tree Frog',
        'n01644900': 'Tailed Frog',
        'n01664065': 'Loggerhead',
        'n01665541': 'Leatherback Turtle',
        'n01667114': 'Mud Turtle',
        'n01667778': 'Terrapin',
        'n01669191': 'Box Turtle',
        'n01677366': 'Banded Gecko',
        'n01682714': 'Common Iguana',
        'n01685808': 'American Chameleon',
        'n01687978': 'Whiptail',
        'n01688243': 'Agama',
        'n01689811': 'Frilled Lizard',
        'n01692333': 'Alligator Lizard',
        'n01693334': 'Gila Monster',
        'n01694178': 'Green Lizard',
        'n01695060': 'African Chameleon',
        'n01697457': 'Komodo Dragon',
        'n01698640': 'African Crocodile',
        'n01704323': 'American Alligator',
        'n01728572': 'Thunder Snake',
        'n01728920': 'Ringneck Snake',
        'n01729322': 'Hognose Snake',
        'n01729977': 'Green Snake',
        'n01734418': 'King Snake',
        'n01735189': 'Garter Snake',
        'n01737021': 'Water Snake',
        'n01739381': 'Vine Snake',
        'n01740131': 'Night Snake',
        'n01742172': 'Boa Constrictor',
        'n01744401': 'Rock Python',
        'n01748264': 'Indian Cobra',
        'n01749939': 'Green Mamba',
        'n01751748': 'Sea Snake',
        'n01753488': 'Horned Viper',
        'n01755581': 'Diamondback',
        'n01756291': 'Sidewinder',

        # Fish
        'n01440764': 'Tench',
        'n01443537': 'Goldfish',
        'n01484850': 'Great White Shark',
        'n01491361': 'Tiger Shark',
        'n01494475': 'Hammerhead',
        'n01496331': 'Electric Ray',
        'n01498041': 'Stingray',
    }
    return animal_categories

def get_image_info(image_path):
    img = Image.open(image_path)
    arr = np.array(img)
    info = {
        "format": img.format,
        "mode": img.mode,
        "width": img.width,
        "height": img.height,
        "channels": len(img.getbands()),
        "file_size_bytes": os.path.getsize(image_path),
        "mean_color":    np.round(arr.mean(axis=(0,1)),2).tolist(),
        "std_color":     np.round(arr.std(axis=(0,1)),2).tolist(),
    }
    # attempt to read EXIF
    exif_data = {}
    raw_exif = getattr(img, "_getexif", lambda: None)()
    if raw_exif:
        for tag, val in raw_exif.items():
            name = ExifTags.TAGS.get(tag, tag)
            exif_data[name] = val
    info["exif"] = exif_data
    return info

def classify_image(image_path):
    try:
        # gather image metadata
        image_info = get_image_info(image_path)

        # Load model + metadata
        weights    = models.ResNet50_Weights.IMAGENET1K_V1
        model      = models.resnet50(weights=weights)
        model.eval()
        class_names= weights.meta["categories"]   # human-readable list of 1000 names

        # Transforms
        transform = transforms.Compose([
            transforms.Resize(256),
            transforms.CenterCrop(224),
            transforms.ToTensor(),
            transforms.Normalize(
                mean=[0.485,0.456,0.406],
                std=[0.229,0.224,0.225]
            )
        ])

        # Inference
        img = Image.open(image_path).convert("RGB")
        tensor = transform(img).unsqueeze(0)
        with torch.no_grad():
            out = model(tensor)
        probs = torch.nn.functional.softmax(out, dim=1)[0]
        _, idxs = torch.topk(probs, 10)
        idxs = idxs.tolist()

        results     = []
        animal_type = "Unknown"

        for idx in idxs:
            pretty = class_names[idx]         # e.g. "Siamese cat"
            p = float(probs[idx])
            conf = min(100, max(0, int(p*100)))

            # Category rules on pretty.lower()
            nm = pretty.lower()
            if any(x in nm for x in ["dog","hound","terrier","spaniel","retriever","shepherd","collie"]):
                cat="Dog"
            elif any(x in nm for x in ["cat","siamese","persian","tabby"]):
                cat="Cat"
            elif any(x in nm for x in ["shark","ray","fish","tench","goldfish"]):
                cat="Fish"
            elif any(x in nm for x in ["snake","boa","viper","cobra","python","mamba"]):
                cat="Snake"
            elif any(x in nm for x in ["lizard","gecko","iguana","chameleon","dragon","agama"]):
                cat="Lizard"
            elif any(x in nm for x in ["turtle","terrapin","loggerhead","leatherback"]):
                cat="Turtle"
            elif any(x in nm for x in ["frog","toad","salamander","newt","axolotl","eft"]):
                cat="Amphibian"
            elif any(x in nm for x in ["eagle","robin","finch","owl","hen","cock","jay","magpie"]):
                cat="Bird"
            elif any(x in nm for x in ["bear","lion","tiger","leopard","cheetah","lynx"]):
                cat="Big Cat/Bear"
            elif any(x in nm for x in ["gorilla","orangutan","chimpanzee"]):
                cat="Primate"
            elif any(x in nm for x in ["crocodile","alligator"]):
                cat="Crocodilian"
            else:
                cat="Other"

            if not results:
                animal_type = cat

            results.append({
                "breed":      pretty,
                "category":   cat,
                "confidence": conf
            })

        result = {
            "success":      True,
            "animal_type":  animal_type,
            "predictions":  results[:5],
            "image_info":   image_info
        }
        return result

    except Exception as e:
        return {"success": False, "error": str(e)}

# Run the classification and print the result as JSON
result = classify_image(image_path)
print(json.dumps(result))

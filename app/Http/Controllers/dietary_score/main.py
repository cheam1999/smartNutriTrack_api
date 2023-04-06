# -*- coding: utf-8 -*-
"""
Created on Thu Mar 30 21:01:43 2023

@author: ACER
"""


from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import pickle
import json
import pandas as pd

app = FastAPI()

origins = ["*"]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials = True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class model_input(BaseModel):
    
    Carbohydrate : float
    Protein: float
    Sodium: float
    Calcium: float
    
class model_output(BaseModel):
    
    Carb: int
    Protein: int
    Sodium: int
    Calcium: int
    Score: float


dietary_model = pickle.load(open('dietary_score.sav','rb'))
carb_model = pickle.load(open('carb_model.sav','rb'))
protein_model = pickle.load(open('protein_model.sav','rb'))
sodium_model = pickle.load(open('sodium_model.sav','rb'))
calcium_model = pickle.load(open('calcium_model.sav','rb'))

@app.post('/dietary_score')

def dietary_pred(input_parameters: model_input):
    
    input_data = input_parameters.json()
    input_dictionary = json.loads(input_data)
    
    carb = input_dictionary['Carbohydrate']
    protein = input_dictionary['Protein']
    sodium = input_dictionary['Sodium']
    calcium = input_dictionary['Calcium']
    
    # compute nutrient level
    carb_level = carb_model.predict([[carb]])
    protein_level = protein_model.predict([[protein]])
    sodium_level = sodium_model.predict([[sodium]])
    calcium_level = calcium_model.predict([[calcium]])
    
    # compute dietary score
    dietary_model.input['Carbohydrates'] = carb
    dietary_model.input['Protein'] = protein
    dietary_model.input['Sodium'] = sodium
    dietary_model.input['Calcium'] = calcium
    
    dietary_model.compute()
    
    output = {
        "Carb": carb_level,
        "Protein": protein_level,
        "Sodium": sodium_level,
        "Calcium": calcium_level,
        "Score": dietary_model.output['Score']
        }
    
    #output_json = json.dumps(output)
    output_json = pd.Series(output).to_json(orient='values')
    
    return output_json

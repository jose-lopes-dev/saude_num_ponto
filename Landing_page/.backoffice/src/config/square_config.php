<?php
define("SQUARE_ENV", "sandbox");
define("SQUARE_ACCESS_TOKEN", "EAAAl1Ui2A0-Ih3wWSkqPNZ4ZWj4LlFlX2CdAzLGvoxF-R02KblqTsDTMaA1n2Hr");
define("SQUARE_LOCATION_ID", "L4E7K292JPS2A");

function squareBaseUrl(){
    if(SQUARE_ENV == "sandbox"){
        return "https://connect.squareupsandbox.com";
    }else{
        return "https://connect.squareup.com";
    }
}

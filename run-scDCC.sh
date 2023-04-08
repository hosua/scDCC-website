#!/bin/bash
data_file="$1"
lc_val="$2"
hc_val="$3"

if [ -z "$data_file" ]; then echo "Error: Needs input file as parameter"; exit 1; fi
if [ -z "$lc_val" ]; then lc_val=20; fi
if [ -z "$hc_val" ]; then hc_val=90; fi

echo "Running scDCC..."
source activate scDCC-venv \
	&& python3 scDCC_web.py \
			--data_file "$data_file" \
			--lc $lc_val \
			--hc $hc_val \
	&& source deactivate

echo "Generated with: 
lc=$lc_val, hc=$hc_val
data_file=" > details.txt

zip output.zip details.txt log.txt latent_p0_1.txt pred_y_latent_p0_1.txt

mv latent_p0_1.txt pred_y_latent_p0_1.txt output.zip scDCC-website/site/downloads


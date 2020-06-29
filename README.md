# TrainingApiGraphql

Magento Module for training

## Installation

```sh
mkdir app/code/Icube
cd app/code/Icube
git clone https://github.com/chipulaja/training-graphql-backend-magento.git TrainingApiGraphql
cd TrainingApiGraphql
git fetch origin
checkout apigraphql
cd ../../../../
bin/magento setup:upgrade --keep-generated
bin/magento setup:di:compile
```

#!/bin/bash

set -e

if [ -z "$AWS_ACCESS_KEY_ID" ]; then
    echo 'Please define the variable values for AWS_ACCESS_KEY_ID'         
    exit 1
fi

if [ -z "$AWS_SECRET_ACCESS_KEY" ]; then
    echo 'Please define the variable values for AWS_SECRET_ACCESS_KEY'         
    exit 1
fi

if [ -z "$AWS_DEFAULT_REGION" ]; then
    echo 'Please define the variable values for AWS_DEFAULT_REGION'         
    exit 1
fi


terraform init  && \
terraform get && \
terraform plan -out=tfplan && \
terraform validate && \
terraform apply   && \
terraform output -json >  output.json
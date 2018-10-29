# drunomics docker devsetup

docker setup based upon https://github.com/drunomics/devsetup-amazeeio

Features:
- utilizes docker-compose for local development (WIP)
- supports drunomics' CI system - lupus-CI
- compatible to amazeeio.'s lagoon - see https://lagoon.readthedocs.io/en/latest/ 


## Setup

From your drupal-project root directory, run:

    git clone https://github.com/drunomics/devsetup-docker --branch=2.x devsetup-tmp
    rm -rf devsetup-tmp/.git devsetup-tmp/README.md
    cp -rfT devsetup-tmp devsetup-tmp .
        
    # Apply replacements and cleanup.
    php process-replacements.php
    rm -rf devsetup-tmp process-replacements.php

If you do not use lagoon, you can delete related files:

    rm -f .lagoon.yml dotenv/amazeeio.env

Then commit changes:

    git add .
    git commit -am "Added docker devsetup."

## Lagoon
    
Follow the lagoon docs for finishing the setup on amazeeio:

  https://lagoon.readthedocs.io/en/latest/using_lagoon/setup_project/#2-provide-access-to-your-code

## Credits

* Based upon the setup provided by amazee.io, see 
  https://github.com/amazeeio/drupal-example
* (c) 2018 drunomics GmbH, GNU GPLv2+

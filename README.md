![plot](./src/public/img/logo.svg)

# Wallet Transaction Engine (WTE)

- [Wallet Transaction Engine (WTE)](#wallet-transaction-engine--wte-)
    * [Overview](#overview)
    * [Features](#features)
        + [Authentication](#authentication)
        + [Users and Wallet Accounts](#users-and-wallet-accounts)
    * [Application Versions](#application-versions)
        + [Version 1](#version-1)
        + [Version 2](#version-2)
    * [Local Development](#local-development)
        + [Prerequisites](#prerequisites)
        + [Docker Windows Configuration](#docker-windows-configuration)
            - [WSL v1 (recommended)](#wsl-v1--recommended-)
            - [WSL v2](#wsl-v2)
    * [Installation](#installation)
        + [1. Pull this repository](#1-pull-this-repository)
        + [2. Build the Docker image](#2-build-the-docker-image)
        + [3. Add this image to your docker-compose.yaml](#3-add-this-image-to-your-docker-composeyaml)
    * [Usage](#usage)
        + [API Documentation](#api-documentation)

## Overview
Wallet Transaction Engine is a stateless REST API service for the purpose of processing Wallet transactions and
recording Financial Journal entries. This application is compliant with Double Entry Accounting standards for
bank ledgers.

## Features
WTE is capable of (but not limited to) the following features:
- Processing of debit/credit fund movements between accounts
- Reverse transactions
- Record and log financial journal entries
- Notify transactional exceptions through Slack channels
- Perform OAuth 2.0 validation for all requests

### Authentication
This application will require an OAuth 2.0 token to be provided for each request in the form of a
"Bearer Token" authentication HTTP Header.

Due to the nature of OAuth 3-legged flows, WTE depends on an external
OAuth 2.0 server to be configured.

### Users and Wallet Accounts
The present application does not provide migrations or any provision of User or Wallet data.
Such information must be provisioned to this application for it to handle transactions.

## Application Versions
WTE ships with 2 versions. Although there are breaking changes between them, the nature of the
application remains the same; to process and record transactions.

### Version 1
This version allows for Client Credentials Grant tokens to authenticate and perform fund movements
and balance inquiries on behalf of the Server's users. This version was created in order to provide
backwards compatibility with some legacy banking systems in the Philippines and Singapore where it
is common to find systems that don't implement Auth Code Grant authorization, so Client Credentials
Grant is easier to configure.

>This version will be deprecated in the future in favour of Version 2.

### Version 2
The latest version of WTE forces all credit/debit related requests to be authenticated using a
Auth Code Grant (i.e. using an Access Token that belongs to a User). This provides tighter security
when executing transactions.

Additionally, Version 2 uses a different Database Schema, so transactions will be stored differently.
This new schema is more efficient and enables faster queries, but does imply migrating data from the
V1 model to V2. So any systems using WTE V1 but wanting to use V2 must ensure they have the capability
of either using both versions simultaneously or stick to one.

## Local Development

### Prerequisites
Please install in order and ensure to read the instructions thoroughly.

1. [Install Git](https://git-scm.com/downloads)

2. [Install Composer](https://getcomposer.org/download/)

3. [Install Docker Desktop](https://docs.docker.com/desktop/install/windows-install/)

4. [Install and Configure AWS CLI](https://aws.amazon.com/cli/)

### Docker Windows Configuration

#### WSL v1 (recommended)
This is the recommended WSL engine version for this container because it is
faster for the container to access the shared files between the Linux subsystem
and the Windows host.

>Linux and Windows have different filesystems, so sharing directories between
them takes a long time.

#### WSL v2
If you have **no choice** but running WSL2, you must create a file
`C:/Users/<username>/.wslconfig` with the following contents:

```bash
[wsl2]
memory=8GB # Limits amount of VM memory
processors=4 # Limits number VM virtual processors
```
>You may increase the limits, if your computer has enough resources.

## Installation

### 1. Pull this repository
Clone this repository or copy the files from this repository into a new folder:
```bash
git clone git@github.com:cris005/wte.git
```

### 2. Build the Docker image
Open a terminal, `cd` to the folder in which `docker compose.yml` is saved, and run:
```bash
docker build -t wte .
```
>Please ensure you have all the prerequisites completed before running this
### 3. Add this image to your docker-compose.yml
This application is meant to be used as part of a distributed system made up of microservices. That said, in order to
use this application you will have to add it as a service to your container and interact with it from there.

And then simply run the container.
```bash
docker compose up
```

## Usage

### API Documentation
To generate the API documentation, please run the following command.
```bash
php artisan scribe:generate
```

You will be able to see a list of endpoints as well as examples of request parameters and sample responses by opening
the below URL.
```bash
https://<YOUR_HOST_NAME>/docs
```

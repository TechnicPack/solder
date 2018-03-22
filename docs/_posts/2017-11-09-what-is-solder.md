---
date: 2017-11-09 21:17:03
title: What is Solder?
categories:
  - getting-started
description:
type: Document
---


### Solder is a Modpack API[#](#solder-is-a-modpack-api){: .header-link}

Solder is an API that sits between a modpack repository and the launcher. It allows you to easily manage multiple modpacks in one single location. It’s the same API we use to distribute our modpacks!

Using Solder also means your packs will download each mod individually. This means the launcher can check MD5’s against each version of a mod and if it hasn’t changed, use the cached version of the mod instead. What does this mean? Small incremental updates to your modpack doesn’t mean redownloading the whole thing every time!

Solder also interfaces with the Technic Platform using an API key you can generate through your account there. When Solder has this key it can directly interact with your Platform account. When creating new modpacks you will be able to import any packs you have registered in your Solder install. It will also create detailed mod lists on your Platform page! (assuming you have the respective data filled out in Solder) Neat huh?

### Solder is Open Source[#](#solder-is-open-source){: .header-link}

Solder is licensed under the MIT License. What does this mean? It means its completely open source and you can do anything you want with it! We also appreciate any help with the development of Solder, so fork away and contribute!
ApiAssets
 [x] A user can list assets
 [x] A user can view an asset
 [x] A user can update an asset
 [x] A user can delete an asset
 [x] It returns a 401 if a guest tries to list versions
 [x] It returns a 401 if a guest tries to get a version
 [x] It returns a 401 if a guest tries to patch a version
 [x] It returns a 401 if a guest tries to delete a version
 [x] It returns a 404 when processing a get request if the version does not exist
 [x] It returns a 404 when processing a patch request if the version does not exist
 [x] It returns a 404 when processing a delete request if the version does not exist
 [x] It returns a 409 when processing a patch request and the asset id does not match the servers endpoint
 [x] It returns a 409 when processing a patch request and the asset type does not match the servers endpoint
 [x] It returns a 422 when processing a patch request if the filename is blank

ApiBuilds
 [x] A guest can view a public build
 [x] A guest can view an unlisted build
 [x] A user can view a public build
 [x] A user can view an unlisted build
 [x] A user can view a private modpack
 [x] A user can update a build
 [x] It allows the existing version to be reused
 [x] A user can delete a build
 [x] It returns a 401 if a guest tries to get a private build
 [x] It returns a 401 if a guest tries to patch a build
 [x] It returns a 401 if a guest tries to delete a build
 [x] It returns a 404 when processing a get request if the build does not exist
 [x] It returns a 404 when processing a patch request if the build does not exist
 [x] It returns a 404 when processing a delete request if the build does not exist
 [x] It returns a 409 when processing a patch request and the resource id does not match the servers endpoint
 [x] It returns a 409 when processing a patch request and the resource type does not match the servers endpoint
 [x] It returns a 422 when processing a patch request if the version is blank
 [x] It returns a 422 when processing a patch request if the privacy is not valid
 [x] It returns a 422 when processing a patch request if the arguments is not an array

ApiModpackBuilds
 [x] A guest can list public builds that are part of a public modpack
 [x] A guest can list public builds that are part of an unlisted modpack
 [x] A guests cannot list non public builds
 [x] A user can list all builds
 [x] A user can post a new build
 [x] It lists relationships with public builds for guests
 [x] It returns a 401 when processing a get request for relationships on a private modpack without authentication
 [x] It returns a 401 when processing a get request for builds on a private modpack without authentication
 [x] It returns a 401 when processing a post request without authentication
 [x] It returns a 422 when processing a post request if the version is blank
 [x] It returns a 422 when processing a post request if the version is not unique for the modpack
 [x] It returns a 422 when processing a post request if the privacy is not valid
 [x] It returns a 422 when processing a post request if the arguments is not an array

ApiModpacks
 [x] A guest can list public modpacks
 [x] A guests cannot list non public modpacks
 [x] A user can list all modpacks
 [x] A guest can view a public modpack
 [x] A guest can view an unlisted modpack
 [x] A user can view a public modpack
 [x] A user can view a unlisted modpack
 [x] A user can view a private modpack
 [x] A user can post a new modpack
 [x] A user can update a modpack
 [x] It allows the existing slug to be reused
 [x] A user can binary upload an asset
 [x] A user can delete a modpack
 [x] Response can include build data
 [x] It returns a 401 if a guest tries to get a private modpack
 [x] It returns a 401 if a guest tries to post a modpack
 [x] It returns a 401 if a guest tries to patch a modpack
 [x] It returns a 401 if a guest tries to put an asset
 [x] It returns a 401 if a guest tries to delete a modpack
 [x] It returns a 403 if a put request is made to an unsupported asset endpoint
 [x] It returns a 404 when processing a get request if the modpack does not exist
 [x] It returns a 404 when processing a patch request if the modpack does not exist
 [x] It returns a 404 when processing a put request if the modpack does not exist
 [x] It returns a 404 when processing a delete request if the modpack does not exist
 [x] It returns a 409 when processing a patch request and the resource id does not match the servers endpoint
 [x] It returns a 409 when processing a patch request and the resource type does not match the servers endpoint
 [x] It returns a 411 when processing a put request and the content type is image and the content length is not provided
 [x] It returns a 413 when processing a put request and the content type is image and the payload is too large
 [x] It returns a 415 when processing a put request if the payload is not a supported media type
 [x] It returns a 422 when processing a post request if the name is blank
 [x] It returns a 422 when processing a patch request if the name is blank
 [x] It returns a 422 when processing a post request if the slug is blank
 [x] It returns a 422 when processing a patch request if the slug is blank
 [x] It returns a 422 when processing a post request if the slug is not url safe
 [x] It returns a 422 when processing a patch request if the slug is not url safe
 [x] It returns a 422 when processing a post request if the slug is not unique
 [x] It returns a 422 when processing a patch request if the slug is not unique
 [x] It returns a 422 when processing a post request if the privacy is not valid
 [x] It returns a 422 when processing a patch request if the privacy is not valid
 [x] It returns a 422 when processing a post request if the website is not valid
 [x] It returns a 422 when processing a patch request if the website is not valid
 [x] It returns a 422 when processing a post request if the tags is not an array
 [x] It returns a 422 when processing a patch request if the tags is not an array

ApiResourceVersions
 [x] A user can list all versions of a resource
 [x] A user can post a new version
 [x] It lists relationships data
 [x] It returns a 401 when processing a get request for relationships without authentication
 [x] It returns a 401 when processing a get request for versions without authentication
 [x] It returns a 401 when processing a post request without authentication
 [x] It returns a 422 when processing a post request if the version is blank
 [x] It returns a 422 when processing a post request if the version is not unique for the modpack
 [x] Posting a version with the same version attribute as a different resource version is ok

ApiResources
 [x] A user can list resources
 [x] A user can view a resource
 [x] A user can post a new resource
 [x] A user can update a resource
 [x] A user can delete a resource
 [x] It allows the existing slug to be reused
 [x] It returns a 401 if a guest tries to list resources
 [x] It returns a 401 if a guest tries to get a resource
 [x] It returns a 401 if a guest tries to post a resource
 [x] It returns a 401 if a guest tries to patch a resource
 [x] It returns a 401 if a guest tries to delete a resource
 [x] It returns a 404 when processing a get request if the resource does not exist
 [x] It returns a 404 when processing a patch request if the modpack does not exist
 [x] It returns a 404 when processing a delete request if the modpack does not exist
 [x] It returns a 409 when processing a patch request and the resource id does not match the servers endpoint
 [x] It returns a 409 when processing a patch request and the resource type does not match the servers endpoint
 [x] It returns a 422 when processing a post request if the name is blank
 [x] It returns a 422 when processing a patch request if the name is blank
 [x] It returns a 422 when processing a post request if the slug is blank
 [x] It returns a 422 when processing a patch request if the slug is blank
 [x] It returns a 422 when processing a post request if the slug is not url safe
 [x] It returns a 422 when processing a patch request if the slug is not url safe
 [x] It returns a 422 when processing a post request if the slug is not unique
 [x] It returns a 422 when processing a patch request if the slug is not unique

ApiRoot
 [x] It returns the current version of the api

ApiVersionAssets
 [x] A user can list all assets of a version
 [x] A user can post a new asset
 [x] It lists relationships data
 [x] It returns a 401 when processing a get request for relationships without authentication
 [x] It returns a 401 when processing a get request for assets without authentication
 [x] It returns a 401 when processing a post request without authentication
 [x] It returns a 422 when processing a post request if the filename is blank

ApiVersions
 [x] A user can list versions
 [x] A user can view a version
 [x] A user can update a version
 [x] A user can delete a version
 [x] It returns a 401 if a guest tries to list versions
 [x] It returns a 401 if a guest tries to get a version
 [x] It returns a 401 if a guest tries to patch a version
 [x] It returns a 401 if a guest tries to delete a version
 [x] It returns a 404 when processing a get request if the version does not exist
 [x] It returns a 404 when processing a patch request if the version does not exist
 [x] It returns a 404 when processing a delete request if the version does not exist
 [x] It returns a 409 when processing a patch request and the resource id does not match the servers endpoint
 [x] It returns a 409 when processing a patch request and the resource type does not match the servers endpoint
 [x] It returns a 422 when processing a patch request if the version is blank

LegacyApi
 [x] It verifies tokens
 [x] base reply
 [x] It returns a modpack list
 [x] It returns a mod list
 [x] It returns a 404 on invalid modpack
 [x] It returns a modpack by slug
 [x] It returns a 404 on invalid mod
 [x] It returns a mod by slug
 [x] It returns a modpack build by version
 [x] It returns a mod version by version

Asset
 [x] It auto generates v4 uuid for the id column
 [x] Belongs to a version
 [x] It has a location attribute
 [x] It has a filename attribute
 [x] It has an md5 attribute
 [x] It has a filezie attribute

Build
 [x] It auto generates v4 uuid for the id column
 [x] It belongs to a modpack
 [x] Belongs to many versions
 [x] It has a game version attribute


 [x] Belongs to a user

ClientGuard
 [x] User can be retrieved by query string parameter
 [x] Validate can determine if credentials are valid
 [x] Validate if token is empty

Modpack
 [x] It auto generates v4 uuid for the id column
 [x] Has many builds
 [x] It can generate a slug from name on create
 [x] It does not overwrite a slug on name updates
 [x] It accepts a provided slug
 [x] Can get tags as string

Resource
 [x] It auto generates v4 uuid for the id column
 [x] Has many versions
 [x] It can generate a slug from name on create
 [x] It does not overwrite a slug on name updates
 [x] It accepts a provided slug
 [x] It has an author attribute
 [x] It has a description attribute
 [x] It has a website attribute

HasPrivacy
 [x] Priding a privacy string checks the string against the model attribute
 [x] Displayable scopes only public records when no user is provied
 [x] Displayable scope does not limit records when a valid user is provided

User
 [x] Has many legacy tokens

Version
 [x] It auto generates v4 uuid for the id column
 [x] Has many assets
 [x] Belongs to a resource
 [x] Belongs to many builds


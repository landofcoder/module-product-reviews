# Version 1.0.3 (composer version 1.0.3)- 08/25/2021
- Fix issue loading list reviews on frontend because the module Lof_CustomerAvatar was not setup.
- Fix issue when submit new reviews, the email was not sent then show exception error.
- Fix issue when edit review gallery images on backends.
- Update little css code to style for review listing on frontend.

# Version 1.0.5 (composer version 1.0.4) - 02/16/2022
- Refactor code
- Fix bugs and compatible for magento 2.4.0 - 2.4.x
- Fully support REST API
- Upgrade database tables add some columns
- Refactor list reviews
- Update email templates

# Version 1.0.6 (composer version 1.0.4)- 02/16/2022
- Refactor REST API
- Add new column updated_at for table "lof_review_reply"

# Version 1.0.6 (composer version 1.0.4.1) - 03/21/2022
- Refactor REST API Submit Product Review, dont need entity_pk_id value for post data
- Update API doc
- New Rest api get list avaibale product ratings

# Version 1.0.8 - 09/22/2022
- Fix wrong table name in native sql query in model CustomReview

# Version 1.0.9 - 03/14/2023
- Fix issue when submit product review on frontend
- Support store view email templates

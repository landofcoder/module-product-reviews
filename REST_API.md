## REST API Doc

Common rest api docs

### Frontend
1. GET product reviews (similar view list reviews in product detail page):

- Endpoint: [GET] http://[youdomain]/index.php/rest/all/V1/products/[product_sku]/reviews

- Support Params:
- **keyword** : string - search reviews by keyword match in review detail content
- **limit** : int - limit return review items
- **page** : int - paging value
- **sort_by** : string - support empty value or enum value: helpful, rating, latest, oldest, recommended, verified, default

- Response:

```
{
  "total_records": 0,
  "rating_summary": 0,
  "rating_summary_value": 0,
  "recomended_percent": 0,
  "detailed_summary": {
    "one": 0,
    "two": 0,
    "three": 0,
    "four": 0,
    "five": 0,
    "rating_summary": 0,
    "reviews_count": 0
  },
  "items": [
    {
      "id": 0,
      "title": "string",
      "detail": "string",
      "nickname": "string",
      "customer_id": 0,
      "ratings": [
        {
          "vote_id": 0,
          "rating_id": 0,
          "rating_name": "string",
          "percent": 0,
          "value": 0
        }
      ],
      "comments": [
        {
          "reply_id": 0,
          "reply_customer_id": 0,
          "parent_reply_id": 0,
          "review_id": 0,
          "customer_id": 0,
          "admin_user_id": 0,
          "status": 0,
          "reply_title": "string",
          "reply_comment": "string",
          "user_name": "string",
          "website": "string",
          "email_address": "string",
          "avatar_url": "string",
          "created_at": "string"
        }
      ],
      "galleries": {
        "id": 0,
        "review_id": 0,
        "label": "string",
        "value": "string",
        "images": "string",
        "status": true
      },
      "images": [
        {
          "full_path": "string",
          "resized_path": "string"
        }
      ],
      "customize": {
        "review_customize_id": 0,
        "is_recommended": 0,
        "verified_buyer": 0,
        "answer": "string",
        "advantages": "string",
        "disadvantages": "string",
        "average": "string",
        "count_helpful": 0,
        "count_unhelpful": 0,
        "total_helpful": 0,
        "report_abuse": 0,
        "review_id": 0,
        "email_address": "string",
        "avatar_image": "string",
        "avatar_url": "string",
        "country": "string"
      },
      "review_entity": "string",
      "review_type": 0,
      "review_status": 0,
      "created_at": "string",
      "reply_total": 0,
      "entity_pk_value": 0,
      "store_id": 0,
      "stores": [
        0
      ],
      "verified_buyer": 0,
      "is_recommended": true,
      "answer": "string",
      "like_about": "string",
      "not_like_about": "string",
      "guest_email": "string",
      "plus_review": 0,
      "minus_review": 0,
      "country": "string"
    }
  ]
}
```
2. POST guest reply review:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/reviews/guest/reply

- Support Params:

```
{
  "reply": {
    "parent_reply_id": 0,
    "review_id": 0,
    "reply_title": "string",
    "reply_comment": "string",
    "user_name": "string",
    "website": "string",
    "email_address": "string"
  }
}
```

- Response:

```
{
  "reply_id": 0,
  "reply_customer_id": 0,
  "parent_reply_id": 0,
  "review_id": 0,
  "customer_id": 0,
  "admin_user_id": 0,
  "status": 0,
  "reply_title": "string",
  "reply_comment": "string",
  "user_name": "string",
  "website": "string",
  "email_address": "string",
  "avatar_url": "string",
  "created_at": "string"
}
```

3. POST submit reply a review for logged in customer:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/reviews/me/reply

- Params: searchCriteria params

```
{
  "reply": {
    "parent_reply_id": 0,
    "review_id": 0,
    "reply_title": "string",
    "reply_comment": "string",
    "user_name": "string",
    "website": "string",
    "email_address": "string"
  }
}
```

- Response:

```
{
  "reply_id": 0,
  "reply_customer_id": 0,
  "parent_reply_id": 0,
  "review_id": 0,
  "customer_id": 0,
  "admin_user_id": 0,
  "status": 0,
  "reply_title": "string",
  "reply_comment": "string",
  "user_name": "string",
  "website": "string",
  "email_address": "string",
  "avatar_url": "string",
  "created_at": "string"
}
```

4. GET my submited reviews for logged in customer:

- Endpoint: [GET] http://[youdomain]/index.php/rest/V1/reviews/me?searchCriteria%5BpageSize%5D=5&searchCriteria%5BcurrentPage%5D=1

- Params: searchCriteria params

- Response:

```
{
  "items": [
    {
      "id": 0,
      "title": "string",
      "detail": "string",
      "nickname": "string",
      "customer_id": 0,
      "ratings": [
        {
          "vote_id": 0,
          "rating_id": 0,
          "rating_name": "string",
          "percent": 0,
          "value": 0
        }
      ],
      "comments": [
        {
          "reply_id": 0,
          "reply_customer_id": 0,
          "parent_reply_id": 0,
          "review_id": 0,
          "customer_id": 0,
          "admin_user_id": 0,
          "status": 0,
          "reply_title": "string",
          "reply_comment": "string",
          "user_name": "string",
          "website": "string",
          "email_address": "string",
          "avatar_url": "string",
          "created_at": "string"
        }
      ],
      "galleries": {
        "id": 0,
        "review_id": 0,
        "label": "string",
        "value": "string",
        "images": "string",
        "status": true
      },
      "images": [
        {
          "full_path": "string",
          "resized_path": "string"
        }
      ],
      "customize": {
        "review_customize_id": 0,
        "is_recommended": 0,
        "verified_buyer": 0,
        "answer": "string",
        "advantages": "string",
        "disadvantages": "string",
        "average": "string",
        "count_helpful": 0,
        "count_unhelpful": 0,
        "total_helpful": 0,
        "report_abuse": 0,
        "review_id": 0,
        "email_address": "string",
        "avatar_image": "string",
        "avatar_url": "string",
        "country": "string"
      },
      "review_entity": "string",
      "review_type": 0,
      "review_status": 0,
      "created_at": "string",
      "reply_total": 0,
      "entity_pk_value": 0,
      "store_id": 0,
      "stores": [
        0
      ],
      "verified_buyer": 0,
      "is_recommended": true,
      "answer": "string",
      "like_about": "string",
      "not_like_about": "string",
      "guest_email": "string",
      "plus_review": 0,
      "minus_review": 0,
      "country": "string"
    }
  ],
  "search_criteria": {
    "filter_groups": [
      {
        "filters": [
          {
            "field": "string",
            "value": "string",
            "condition_type": "string"
          }
        ]
      }
    ],
    "sort_orders": [
      {
        "field": "string",
        "direction": "string"
      }
    ],
    "page_size": 0,
    "current_page": 0
  },
  "total_count": 0
}
```

5. GET my review detail by review id:

- Endpoint: [GET] http://[youdomain]/index.php/rest/V1/reviews/me/[reviewId]

- Params: 

**reviewId**: int

- Response: Review Data Object

6. POST verify purchase for customer before review:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/reviews/me/verify
- Params:

```
{
  "data": {
    "order_id": "string",
    "email": "string",
    "sku": "string"
  }
}
```
- Response: bool

7. POST like a review for logged in customer:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/reviews/me/{reviewId}/like
- Params:
**reviewId** : Int

- Response: Review Data Object

8. POST dislike a review for logged in customer:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/reviews/me/{reviewId}/unlike
- Params:
**reviewId** : Int

- Response: Review Data Object

9. POST report a review for logged in customer:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/reviews/me/{reviewId}/report
- Params:
**reviewId** : Int

- Response: Review Data Object

10. POST new review of product for logged in customer:

- Endpoint: [POST] http://[youdomain]/index.php/rest/V1/products/{sku}/reviews
- Params:
**sku** : String
**review data** :
```
{
  "review": {
    "title": "string",
    "detail": "string",
    "nickname": "string",
    "ratings": [
      {
        "rating_id": 0,
        "rating_name": "string",
        "value": 0
      }
    ],
    "images": [
      {
        "full_path": "string"
      }
    ],
    "entity_pk_value": 0,
    "store_id": 0,
    "stores": [
      0
    ],
    "like_about": "string",
    "not_like_about": "string",
    "guest_email": "string",
    "country": "string"
  }
}
```
- entity_pk_value : that is product id

- Response: 

```
{
  "id": 0,
  "title": "string",
  "detail": "string",
  "nickname": "string",
  "customer_id": 0,
  "ratings": [
    {
      "vote_id": 0,
      "rating_id": 0,
      "rating_name": "string",
      "percent": 0,
      "value": 0
    }
  ],
  "comments": [
    {
      "reply_id": 0,
      "reply_customer_id": 0,
      "parent_reply_id": 0,
      "review_id": 0,
      "customer_id": 0,
      "admin_user_id": 0,
      "status": 0,
      "reply_title": "string",
      "reply_comment": "string",
      "user_name": "string",
      "website": "string",
      "email_address": "string",
      "avatar_url": "string",
      "created_at": "string"
    }
  ],
  "galleries": {
    "id": 0,
    "review_id": 0,
    "label": "string",
    "value": "string",
    "images": "string",
    "status": true
  },
  "images": [
    {
      "full_path": "string",
      "resized_path": "string"
    }
  ],
  "customize": {
    "review_customize_id": 0,
    "is_recommended": 0,
    "verified_buyer": 0,
    "answer": "string",
    "advantages": "string",
    "disadvantages": "string",
    "average": "string",
    "count_helpful": 0,
    "count_unhelpful": 0,
    "total_helpful": 0,
    "report_abuse": 0,
    "review_id": 0,
    "email_address": "string",
    "avatar_image": "string",
    "avatar_url": "string",
    "country": "string"
  },
  "review_entity": "string",
  "review_type": 0,
  "review_status": 0,
  "created_at": "string",
  "reply_total": 0,
  "entity_pk_value": 0,
  "store_id": 0,
  "stores": [
    0
  ],
  "verified_buyer": 0,
  "is_recommended": true,
  "answer": "string",
  "like_about": "string",
  "not_like_about": "string",
  "guest_email": "string",
  "plus_review": 0,
  "minus_review": 0,
  "country": "string"
}
```

### Backend

1. Get reminders list

2. Get review reports

3. GET review galleries

4. GET review comments

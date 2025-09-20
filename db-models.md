- Category

  - id
  - name

- Subcategory

  - id
  - name
  - category_id

- Model

  - id
  - name
  - subcategory_id

- Submodel

  - id
  - name
  - model_id

- CategoryAttribute

  - id
  - name
  - category_id

- Advertising

  - id
  - title
  - description
  - price
  - user_id
  - category_id
  - subcategory_id
  - model_id
  - submodel_id
  - created_at
  - updated_at

- AdvertisingAttributeSubcategory
  - id
  - advertising_id
  - attribute_subcategory_id
  - value

Example Relationships:

- Category

  - id: 1
  - name: Vehicles

  ***

- Subcategory

  - id: 1
  - name: Cars
  - category_id: 1

  ***

  - id: 2
  - name: Motorcycles
  - category_id: 1

- Model

  - id: 1
  - name: Toyota
  - subcategory_id: 1

- Submodel

  - id: 1
  - name: Camry
  - model_id: 1

- CategoryAttribute
  - id: 1
  - name: Color
  - category_id: 1
  ***
  - id: 2
  - name: Year
  - category_id: 1
  ***
  - id: 3
  - name: Make
  - category_id: 1

User Story Example:

I want to sell my car, so I create an advertising post under the "Vehicles" category, selecting "Cars" as the subcategory, "Toyota" as the model, and "Camry" as the submodel.

- Title, description, and price are filled in to attract potential buyers.

* Advertising

  - id: 1
  - title: "2018 Toyota Camry for Sale"
  - description: "Well-maintained, single owner, low mileage."
  - price: 20000
  - user_id: 1
  - category_id: 1 -> Send list of subcategories and attributes
  - subcategory_id: 1 -> Send list of models
  - model_id: 1 -> Send list of submodels
  - submodel_id: 1
  - created_at: "2023-01-15T10:00:00Z"
  - updated_at: "2023-01-15T10:00:00Z"

* AdvertisingAttributeSubcategory
  - id: 1
  - advertising_id: 1
  - category_attribute_id: 1
  - value: "Red"
  ---
  - id: 2
  - advertising_id: 1
  - category_attribute_id: 2
  - value: "2018"
  ---
  - id: 3
  - advertising_id: 1
  - category_attribute_id: 3
  - value: "Toyota"

When user submit the frontend will send request with json payload like this:

```json
{
  "title": "2018 Toyota Camry for Sale",
  "description": "Well-maintained, single owner, low mileage.",
  "price": 20000,
  "user_id": 1,
  "category_id": 1,
  "subcategory_id": 1,
  "model_id": 1,
  "submodel_id": 1,
  "attributes": [
    {
      "attribute_id": 1,
      "value": "Red"
    },
    {
      "attribute_id": 2,
      "value": "2018"
    },
    {
      "attribute_id": 3,
      "value": "Toyota"
    }
  ]
}
```

Backend Laravel Controller Example:

```php
public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'user_id' => 'required|integer|exists:users,id',
        'category_id' => 'required|integer|exists:categories,id',
        'subcategory_id' => 'required|integer|exists:subcategories,id',
        'model_id' => 'required|integer|exists:models,id',
        'submodel_id' => 'nullable|integer|exists:submodels,id',
        'attributes' => 'array',
        'attributes.*.attribute_id' => 'required|integer|exists:category_attributes,id',
        'attributes.*.value' => 'required|string|max:255',
    ]);

    // Create the advertising post
    $advertising = Advertising::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'price' => $validated['price'],
        'user_id' => $validated['user_id'],
        'category_id' => $validated['category_id'],
        'subcategory_id' => $validated['subcategory_id'],
        'model_id' => $validated['model_id'],
        'submodel_id' => $validated['submodel_id'] ?? null,
    ]);

    $bulkInsertData = [];
    $now = now();
    // Save the attributes
    foreach ($validated['attributes'] ?? [] as $attribute) {
        $bulkInsertData[] = [
            'advertising_id' => $advertising->id,
            'category_attribute_id' => $attribute['attribute_id'],
            'value' => $attribute['value'],
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    AdvertisingAttributeSubcategory::insert($bulkInsertData);
    return response()->json(['message' => 'Advertising created successfully', 'advertising' => $advertising], 201);
}

```

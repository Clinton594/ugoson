<?php
$year    = date("Y");
$reports = [
  "inventoryReport"=>[
    "table"=>"item",
    "primary_key"=>"tid",
    "page_title"=>"Inventory Report",
    "icon"=>"bubble_chart",
    "report_type"=>"basics",
    "noTrans"=>1,
    "sort"=>"description",
    "group"=>"",
    "retrieve_filter"=>"master_stock_id<>0 AND quantity_on_hand<>0",
    "report_setup"=>[
      "display_fields"=>[
        [
          "column"=>"itemid",
          "name"=>"itemId",
          "description"=>"Item ID",
          "grouping"=>true,
          "checked"=>true,
          "filter"=>true,
          "source"=>[
            "pageType"=>"all-item",
            "column" => ["itemid"]
          ],
          "type"=>"text",
        ],
        [
          "column"=>"description",
          "name"=>"description",
          "description"=>"Item Description",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"upc",
          "description"=>"UPC Code",
          "name"=>"upc",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"unit",
          "name"=>"unit",
          "description"=>"Unit",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"weight",
          "grouping"=>true,
          "checked"=>true,
          "name"=>"weight",
          "description"=>"Weight",
          "type"=>"number",
        ],
        [
          "column"=>"unit_cost",
          "name"=>"unit_cost",
          "description"=>"Unit Cost",
          "checked"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"price1",
          "name"=>"price",
          "description"=>"Price",
          "checked"=>true,
          "graph"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"quantity_on_hand",
          "name"=>"quantity_on_hand",
          "description"=>"Quantity",
          "checked"=>true,
          "graph"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"blank",
          "name"=>"blank",
          "type"=>"number",
          "description"=>"Number Counted",
        ],
        [
          "column"=>"count(*)",
          "name"=>"counted",
          "graph"=>true,
          "type"=>"number",
          "description"=>"Number of Items",
        ],
        [
          "column"=>"present_value",
          "name"=>"present_value",
          "description"=>"Current Value",
        ],
        [
          "column"=>"gl_sales_account",
          "name"=>"gl_sales_account",
          "description"=>"GL Sales Account",
        ],
        [
          "column"=>"gl_inventory_account",
          "name"=>"gl_inventory_account",
          "description"=>"GL Inventory Account",
        ],
        [
          "column"=>"vendor_id",
          "name"=>"vendor_id",
          "description"=>"Prefered Vendor",
        ],
        [
          "column"=>"buyer_id",
          "name"=>"buyer_id",
          "description"=>"Prefered Buyer"
        ],
      ],
      "sections"=>[
        "date",
        "grouping",
        "filters",
      ],
      "datecol"=>""
    ]
  ],

  "bookingReport"=>[
    "table"=>"transactions",
    "primary_key"=>"tid",
    "sort"=>"tid",
    "page_title"=>"Booking Report",
    "report_type"=>"basics",
    "icon"=>"bubble_chart",
    // "noTrans"=>1,
    "retrieve_filter"=>"trans_type='BKG' AND sub<>0",
    "report_setup"=>[
      "display_fields"=>[
        [
          "column"=>"trans_no",
          "name"=>"trans_no",
          "description"=>"Trip ID",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"it_id",
          "name"=>"it_id",
          "description"=>"Trip Route",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
          "source"=>[
            "pageType"=>"routes",
            "column"=>["departure","arrival"],
          ],
        ],
        [
          "column"=>"it_type",
          "name"=>"it_type",
          "description"=>"Vehicle Type",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
          "source"=>"seat_map",
        ],
        [
          "column"=>"cid",
          "name"=>"cid",
          "description"=>"Customer",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
          "source"=>"allUsers",
          // "source"=>[
          //   "pageType"=>"allUsers",
          //   "column"=>["first_name", "last_name"],
          // ],
        ],
        [
          "column"=>"sum(amount)",
          "name"=>"amount",
          "description"=>"Amount Generated",
          "checked"=>true,
          "graph"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"blank",
          "name"=>"blank",
          "type"=>"number",
          "description"=>"Number Counted",
        ],
        [
          "column"=>"count(*)",
          "name"=>"counted",
          "graph"=>true,
          "type"=>"number",
          "description"=>"Number of Passengers",
        ],
        [
          "column"=>"date(date_due)",
          "name"=>"date_due",
          "description"=>"Travel Date",
          "grouping"=>true,
          "type"=>"date",
        ],
        [
          "column"=>"date(date_created)",
          "name"=>"date_created",
          "description"=>"Book Date Date",
          "grouping"=>true,
        ],
      ],
      "sections"=>[
        "date",
        "grouping",
        // "filters",
      ],
      "datecol"=>"date_due"
    ]
  ],

  "tripReport"=>[
    "table"=>"transactions",
    "primary_key"=>"tid",
    "sort"=>"date_due",
    "page_title"=>"Trip Report",
    "report_type"=>"basics",
    "icon"=>"bubble_chart",
    "retrieve_filter"=>"trans_type='BKG' AND sub=0",
    "report_setup"=>[
      "display_fields"=>[
        [
          "column"=>"trans_no",
          "name"=>"trans_no",
          "description"=>"Trip ID",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"it_id",
          "name"=>"description",
          "description"=>"Trip Route",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
          "source"=>[
            "pageType"=>'routes',
            "column"=>["departure","arrival"],
          ],
        ],
        [
          "column"=>"it_type",
          "name"=>"it_type",
          "description"=>"Vehicle Type",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"cid",
          "name"=>"cid",
          "description"=>"Driver",
          "grouping"=>true,
          "filter"=>true,
          "checked"=>true,
          "type"=>"combo",
          "source"=>[
            "pageType"=>"drivers",
            "column"=>["id", "first_name", "last_name"],
          ],
          // "value"=>"id",
          "multiple"=>"first_name, last_name",
        ],
        [
          "column"=>"sum(amount)",
          "name"=>"amount",
          "description"=>"Amount Generated",
          "checked"=>true,
          "graph"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"unit",
          "name"=>"booked",
          "type"=>"text",
          "description"=>"Booked Seats"
        ],
        [
          "column"=>"count(*)",
          "name"=>"counted",
          "graph"=>true,
          "type"=>"number",
          "description"=>"Number of Trips",
        ],
        [
          "column"=>"date(date_due)",
          "name"=>"date_due",
          "description"=>"Trip Date",
          "grouping"=>true,
          "type"=>"date",
        ],
      ],
      "sections"=>[
        "date",
        "grouping",
        "filters",
      ],
      "datecol"=>"date_due"
    ]
  ],

  "financial-report"=>[
    "table"=>"transactions",
    "primary_key"=>"tid",
    "sort"=>"date_created desc",
    "page_title"=>"Financial Report",
    "report_type"=>"basics",
    "icon"=>"bubble_chart",
    "group_trans"=>1,
    "limit"=>10,
    "retrieve_filter"=>"sub<>0",
    "listFAB"=>["financial-report"],
    "report_setup"=>[
      "display_fields"=>[
        [
          "column"=>"trans_no",
          "name"=>"trans_no",
          "description"=>"Trip ID",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"it_id",
          "name"=>"description",
          "description"=>"Trip Route",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
          "source"=>[
            "pageType"=>'routes',
            "column"=>["departure","arrival"],
          ],
        ],
        [
          "column"=>"it_type",
          "name"=>"it_type",
          "description"=>"Vehicle Type",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"cid",
          "name"=>"cid",
          "description"=>"Driver",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
          "value"=>"id",
          "multiple"=>"first_name, last_name",
          "source"=>[
            "pageType"=>"drivers",
            "column"=>["id", "first_name", "last_name"],
          ],
        ],
        [
          "column"=>"sum(amount)",
          "name"=>"amount",
          "description"=>"Amount Generated",
          "checked"=>true,
          "graph"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"blank",
          "name"=>"blank",
          "type"=>"number",
          "description"=>"Number Counted",
        ],
        [
          "column"=>"count(*)",
          "name"=>"counted",
          "graph"=>true,
          "type"=>"number",
          "description"=>"Number of Trips",
        ],
        [
          "column"=>"date(date_due)",
          "name"=>"date_due",
          "description"=>"Trip Date",
          "grouping"=>true,
          "type"=>"date",
        ],
      ],
      "sections"=>[
        "date",
        "grouping",
        "filters",
      ],
      "datecol"=>"date_due"
    ],
    "display_fields"=>[
      [
        "column"=>"sum(gl_amount)",
        "name"=>"today",
        "description"=>"Today",
        "filter"=>getDateValue("Today","date_created")." AND sub<>0",
        "class"=>"col s12 m2",
        "action"=>"myround",
      ],
      [
        "column"=>"sum(gl_amount)",
        "name"=>"yesterday",
        "description"=>"Yesterday",
        "filter"=>getDateValue("Yesterday","date_created")." AND sub<>0",
        "class"=>"col s12 m2",
        "action"=>"myround",
      ],
      [
        "column"=>"sum(gl_amount)",
        "name"=>"this_week",
        "description"=>"This Week",
        "filter"=>getDateValue("This Week","date_created")." AND sub<>0",
        "class"=>"col s12 m2",
        "action"=>"myround",
      ],
      [
        "column"=>"sum(gl_amount)",
        "name"=>"last_week",
        "description"=>"Last Week",
        "filter"=>getDateValue("Last Week","date_created")." AND sub<>0",
        "class"=>"col s12 m2",
        "action"=>"myround",
      ],
      [
        "column"=>"sum(gl_amount)",
        "name"=>"this_month",
        "description"=>"This Month",
        "filter"=>getDateValue("This Month","date_created")." AND sub<>0",
        "class"=>"col s12 m2",
        "action"=>"myround",
      ],
      [
        "column"=>"sum(gl_amount)",
        "name"=>"last_month",
        "description"=>"Last Month",
        "filter"=>getDateValue("Last Month","date_created")." AND sub<>0",
        "class"=>"col s12 m2",
        "action"=>"myround",
      ]
    ],
    "form"=>[
			"sections"=>[
  			[
					"position"=>"left-mini",
					"section_title"=>"General Monthly Transactions",
					"section_elements"=>[
						[
							"column"=>"sum(gl_amount)",
              "name"=>"latest_graph",
							"type"=>"line-graph",
							"source"=>"months",
              "filter"=>"YEAR(date_created)='$year' AND sub<>0 GROUP BY MONTH(date_created)",
							"class"=>"col s12"
						],
  				],
        ],
  			[
					"position"=>"left-mini",
					"section_title"=>"Monthly Expenses",
					"section_elements"=>[
            [
							"column"=>"sum(gl_amount)",
              "name"=>"charges",
							"type"=>"line-graph",
							"source"=>"months",
              "filter"=>"YEAR(date_created)='$year' AND trans_type='EXP' AND sub<>0 GROUP BY MONTH(date_created)",
							"class"=>"col s12"
						]
  				],
        ],
        [
					"position"=>"middle",
					"section_title"=>"Monthly Booking",
					"section_elements"=>[
            [
              "column"=>"sum(gl_amount)",
              "name"=>"monthly_booking",
              "source"=>"months",
              "type"=>"pie-chart",
              "filter"=>"trans_type='BKG' AND sub<>0 AND YEAR(date_created)='$year' GROUP BY MONTH(date_created)",
              "class"=>"col s12"
						]
          ]
				],
				[
					"position"=>"middle",
					"section_title"=>"Monthly Courier",
					"section_elements"=>[
						[
							"column"=>"sum(gl_amount)",
							"name"=>"monthly_courier",
							"source"=>"months",
							"type"=>"pie-chart",
							"filter"=>"trans_type='COU' AND sub<>0 AND YEAR(date_created)='$year' GROUP BY MONTH(date_created)",
							"class"=>"col s12"
						],

					]
				],
				[
					"position"=>"middle",
					"section_title"=>"Monthly Charter",
					"section_elements"=>[
            [
              "column"=>"sum(gl_amount)",
              "name"=>"monthly_charter",
              "source"=>"months",
              "type"=>"pie-chart",
              "filter"=>"trans_type='CRP' AND sub<>0 AND YEAR(date_created)='$year' GROUP BY MONTH(date_created)",
              "class"=>"col s12"
						]
          ]
				],
				[
					"position"=>"right",
					"section_title"=>"Latest Transactions",
					"section_elements"=>[
						[
							"column"=>"latest",
							"required"=>true,
							"source"=>[
                "pageType"=>"financial-report",
                "column" =>["description", "gl_amount"]
              ],
							"type"=>"table",
							"class"=>"col s12"
						],
					]
				],
        [
					"position"=>"left-mini",
					"section_title"=>"Top 5 customers this month",
					"section_elements"=>[
						[
							"column"=>"top5",
							"required"=>true,
							"source"=>[
                "pageType"=>"top5-customers",
                "column" =>["customer", "telephone", "concat('₦', sum(gl_amount))"]
              ],
							"type"=>"table",
							"class"=>"col s12"
						],
					]
				]
  		]
    ]
  ],

  "top5-customers"=>[
    "table"=>"transactions",
		"primary_key"=>"tid",
		"retrieve_filter"=>getDateValue("This Month","date_created")." AND sub<>0 GROUP BY cid",
		"page_title"=>"Top 5 Customers",
		"sort"=>"amount DESC",
		"limit"=>"5",
  ]
];
?>

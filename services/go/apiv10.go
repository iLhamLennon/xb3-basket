package apiv1

import (
	"xb3-basketball/connection"
	"xb3-basketball/model"
)

func countrys() {
	var as_apis []api

	// DB.Where("age > ?", 18).Find(&table)
	result := DB.Order("api_id desc").Limit(1).Find(&as_apis)

	return as_apis
}

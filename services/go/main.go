package main

import (
	"net/http"
	"xb3-basketball/connection"
	"xb3-basketball/apiv1"
)

func main()  {
	r := gin.Default()
	connection.conn()

	r.GET("/country", apiv1.countrys)

	r.Run("localhost:6060")
}

package connection

var DB *gorm.DB

func conn() {
	dsn := "avmcommu_prod:25justCORE*@tcp(127.0.0.1:3306)/allsportsapi?charset=utf8mb4_general_ci&parseTime=True&loc=Local"
	server, err := gorm.Open(mysql.Open(dsn), &gorm.Config{})

	DB = server
}

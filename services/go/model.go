package model

import (
	"time"
)

type api struct {
	api_id			int 		`json:"api_id"`
	key				string 		`json:"key"`
	created_at		time.Time	`json:"created_at"`
	updated_at		time.Time	`json:"updated_at"`
	deleted_at		time.Time	`json:"deleted_at"`
}

type country struct {
	country_key		int 		`json:"country_key"`
	country_name	string 		`json:"country_name"`
	country_flag	string 		`json:"country_flag"`
	sport			string 		`json:"sport"`
	created_at		time.Time	`json:"created_at"`
	updated_at		time.Time	`json:"updated_at"`
	deleted_at		time.Time	`json:"deleted_at"`
}

type league struct {
	league_key		int			`json:"league_key"`
	league_name		string		`json:"league_name"`
	season			int			`json:"season"`
	nba				int			`json:"nba"`
	country			int			`json:"country"`
	sport			string 		`json:"sport"`
	created_at		time.Time	`json:"created_at"`
	updated_at		time.Time	`json:"updated_at"`
	deleted_at		time.Time	`json:"deleted_at"`
}

type standing struct {
	standing_place	int			`json:"standing_place"`
	team_key		int			`json:"team_key"`
	standing_team	string		`json:"standing_team"`
	standing_P		int			`json:"standing_P"`
	league			int			`json:"league"`
	sport			string 		`json:"sport"`
	created_at		time.Time	`json:"created_at"`
	updated_at		time.Time	`json:"updated_at"`
	deleted_at		time.Time	`json:"deleted_at"`
}

type match struct {
	dates			time.Time	`json:"dates"`
	home			int			`json:"home"`
	away			int			`json:"away"`
	quart			int			`json:"quart"`
	hscore			int			`json:"hscore"`
	ascore			int			`json:"ascore"`
	sport			string 		`json:"sport"`
}

type fixture struct {
	dates			time.Time	`json:"dates"`
	home			int			`json:"home"`
	away			int			`json:"away"`
	nhome			string		`json:"nhome"`
	naway			string		`json:"naway"`
	league			int			`json:"league"`
	country			int			`json:"country"`
	sport			string 		`json:"sport"`
}

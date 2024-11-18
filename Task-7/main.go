package main

import (
	"fmt"
	"net/http"
	"os"

	"github.com/PuerkitoBio/goquery"
)

func main() {

	for {
		fmt.Println("Hoş geldiniz!\n")
		fmt.Printf("1- Hacker Haberleri İçin Tuşlayınız.\n")
		fmt.Printf("2- Twitter Trend Top 10 İçin Tuşlayınız.\n")
		fmt.Printf("3- Yavuzlar Web Güvenliği Yayınlanan Makaleler.\n")
		fmt.Printf("4- Çıkış Yap\n")
		var tuslama int
		fmt.Scanln(&tuslama)
		switch tuslama {
		case 1:
			fmt.Printf("-------------------------------------------------------------------------\n")
			fmt.Printf("Güncel Hacker Habeleri\n")
			hackernew()
			fmt.Println("\nBaşka bir işlem yapmak için bir enter'a basın...")
			var key string
			fmt.Scanln(&key)
			if key == "q" {
				fmt.Println("Programdan çıkılıyor...")
				break
			}
		case 2:
			fmt.Printf("-------------------------------------------------------------------------\n")
			fmt.Printf("Twitter Türkiye Top 10\n")
			twiter()
			fmt.Println("\nBaşka bir işlem yapmak için bir enter'a basın...")
			var key string
			fmt.Scanln(&key)
		case 3:
			fmt.Printf("-------------------------------------------------------------------------\n")
			fmt.Printf("Yavuzlar Web Güvenliği Makaleleri\n")
			yavuzlar()
			fmt.Println("\nBaşka bir işlem yapmak için bir enter'a basın...")
			var key string
			fmt.Scanln(&key)
		case 4:
			fmt.Printf("Çıkış Yapılıyor...")
			return
		default:
			fmt.Printf("Geçersiz Seçim.")
		}
	}
}
func twiter() {
	res, _ := http.Get("https://twitter-trends.iamrohit.in/turkey")
	if res.StatusCode != 200 {
		fmt.Println("Hata", res.StatusCode)
		return
	}
	say := 0
	doc, _ := goquery.NewDocumentFromReader(res.Body)

	doc.Find(".tweet").Each(func(i int, s *goquery.Selection) {
		if say >= 10 {

			return
		}
		title := s.Text()
		fmt.Printf("%d.%s\n", i+1, title)
		say++
	})
}
func hackernew() {

	res, _ := http.Get("https://thehackernews.com")

	if res.StatusCode != 200 {
		fmt.Println("Hata", res.StatusCode)
		return
	}
	doc, _ := goquery.NewDocumentFromReader(res.Body)
	file, _ := os.OpenFile("haberler.txt", os.O_CREATE|os.O_WRONLY, 0644)

	defer file.Close()

	doc.Find(".clear.home-right").Each(func(i int, s *goquery.Selection) {
		title := s.Find(".home-title").Text()
		date := s.Find(".h-datetime").Text()
		description := s.Find(".home-desc").Text()
		fmt.Printf("Haber %d:\n", i+1)
		fmt.Printf("Başlık: %s\n", title)
		fmt.Printf("Tarih: %s\n", date)
		fmt.Printf("Açıklama: %s\n", description)
		fmt.Println("-----------")
		fmt.Fprintf(file, "Haber %d:\n", i+1)
		fmt.Fprintf(file, "Başlık: %s:\n", title)
		fmt.Fprintf(file, "Tarih: %s:\n", date)
		fmt.Fprintf(file, "Açıklama: %s:\n", description)
		fmt.Fprintln(file, "-----------")
	})
}
func yavuzlar() {

	res, _ := http.Get("https://docs.yavuzlar.org")

	if res.StatusCode != 200 {
		fmt.Println("Hata", res.StatusCode)
		return
	}
	doc, _ := goquery.NewDocumentFromReader(res.Body)
	a := 1
	doc.Find(".flex.flex-col").Each(func(i int, s *goquery.Selection) {
		if i >= 142 && i <= 163 {
			title := s.Text()
			fmt.Printf("%d.%s\n", a, title)
			a++
		}

	})
}

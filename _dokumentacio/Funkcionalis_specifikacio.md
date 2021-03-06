# 1. Bevezetés

Megrendelő részéről kaptunk egy olyan elképzelt weboldalt amely segíti a munkájukat a tananyagok elkészítésében és könnyíti ezen anyagok eljutását a diákokhoz. 

# 2. Célok

A fejlesztett e-learning alkalmazás célja az oktatás, ismeretanyag átadása a felhasználók számára. A e-learning programot használva kurzusokon vehet részt a regisztrációt és bejelentkezést követően az alkalmazást használó személy. A tananyag elsajátítását kurzusokon keresztül teheti meg, ahol specializást tudásanyagot kaphat az egyes kurzusokon való részvétel által.

 A felhasználő teszteken keresztül ellenőrizheti tudását, mellyel visszajelzést kap az anyag elsajátításának mértékéről. Az oldal felhasználói között eltérő jogosultságok kerülnek kiosztásra. A diákok és a tanárok megkülönböztetése céljából. A tanárok felügyelik a diákok munkáját és segítik a tanulókat a tananyag elsajátításában. Továbbá admin jogosultság is szerepel a josogultságok között, aki a például a diákot tudja hozzárendelni az adott képzéshez, kurzushoz.

# 3. Jelenlegi helyzet

Napjainkban egyre nagyobb igény mutatkozik a távoktatás iránt, de a meglévő alkalmazások még nem minden téren tudják kiszolgálni a tanulni vágyó felhasználókat. A közelmúltban a Covid-19 világjárvány végett a távoktatás világszerte nagyot fejlődött, de még mindig nem eléggé felhasználóbarát a kezelőfelület, nem kap kellően elegendő visszajelzést a felhasználó stb. 

Alkalmazásunk erre nyújt megoldást. A kurzusok leírása kellően informatív, a kurzusokra való jelentkezés egyszerű. A kurzus kezelő felülete nagyon egyszerű, akár kisiskolások számára is könnyen kezelhető. A diákok alapos visszajelzést kapnak a tanyagagban szereplő tesztek által.


# 4. Követelménylista

Felhasználói szintek:
- Adminisztrátor
- Tanár
- Tanuló

Látogatóként és újonnan regisztrált felhasználóként csak a kezdőlap látható, illetve a regisztráció és a bejelentkezés. Tanuló jogosultságot csak tanár és adminisztrátor adhat.

Tanárok által elérhető funkciók:
- létrehozhat/ feltölthet tananyagokat
- létrehozhat/ feltölthet tesztanyagok, kérdések

Tanulók által elérhető funkciók
- tananyagok
- tesztek

Adminisztrátornak mindenhez van jogosultsága



# 5. Használati esetek

Felahasználók:

 - **Adminisztrátor**
 - **Tanár**
 - **Diák**
 - **Vendég**

Tevékenységek:

**Tanár:**
 - Regisztráció
 - Bejelentkezés tanárként
 - Kurzusok létrehozása, megtekintése, szerkesztése és törlése
 - Tananyagok létrehozása, megtekintése, szerkesztése és törlése
 - Feladatok létrehozása, megtekintése, szerkesztése és törlése
 - Diákok előrehaladásának megtekintése
 - Tananyag kategóriák szerkesztése
 - Osztályok létrehozása
 - Felhasználók jogainak menedzselése
 - Jelszó változtatása

**Adminisztrátor:**
 - Regisztráció
 - Bejelentkezés bármely felhasználóként
 - Tanár státusz kiosztása
 - Kurzusok és Felhasználók szerkesztése, törlése

**Diák:**
 - Regisztráció
 - Bejelentkezés diákként
 - Tananyag megtekintése
 - Tesztek megoldása
 - Megoldott tesztek megtekintése
 - Jelszó változtatása

**Vendég:**
 - Kurzus megtekintése


# 6. Képernyőtervek

Képernyőterv a diák / vendég felhasználó szemszögéből:

![image info](./pictures/kepernyoterv1.png)

Képernyőterv a tanár / admin felhasználó szemszögéből:

![image info](./pictures/kepernyoterv2.png)

# 7. Forgatókönyvek

Regisztráció: Az e-learning felület megnyitását követően a kezdőlapon található regisztráció gombra kattintva tudunk regisztrálni. A gombra kattintás után  megadhatjuk a szükséges adatokat, valamint kiválaszthatjuk, hogy Tanárként vagy Tanulóként kívánunk regisztrálni. A Regisztráció gombra kattintva a kezdőlap jelenik meg, amennyiben helyes adatokat adtunk meg.

Bejelentkezés: Az oldal megnyitása után a navigációs sávon a Bejelentkezés gomb segítségével tudunk a korábban már létrehozott fiókunkba belélni. A Bejelentkezés gombra kattintva megadhatjuk felhasználónevünk, valamint jelszavunk. Helyes adatokat megadva és a Bejelentkezés gombra kattintva a fiókunkba lépünk be.

Tananyagok kezelése tanulóként: Tanuló fiókba történő bejelentkezést követően lehetőségünk van a Tananyagok menüpontban különböző tananyagokhoz hozzáférést igémyelni. Ezt a  tananyag nevére, majd a Hozzáférés igénylése gombra kattintva tehetjük meg. Miután a tananyagot létrehozó tanár vagy az adminisztrátor megadja a hozzáférést, olvasni tudjuk a tananyagot.

Tananyagok kezelése tanárként: Tanár jogosultságú felhasználóval bejelentkezve, a Tananyagok menüpontban lehetőségünk van új tananyagokat létrehozni és meglévő, általunk létrehozott tananyagokat szerkeszteni.

Tesztek kezelése tanulóként: Tanuló felhasználóval bejelentkezve a Tesztek menüpontban azon tananyagok tesztjeit/kérdéseit tekinthetjük meg és oldhatjuk meg, amelyekhez korábban hozzáférést kaptunk.

Tesztek kezelése tanárként: Tanár fiókba bejelentkezve, az általunk létrehozott tananyagokhoz lehetőségünk van tesztek/kérdések feltöltésére, a Tesztek menüpontban.

Hozzáférés megadása tanulónak: Tanár fiókkal bejelentkezve a Kérések menüpontban láthatjuk azon diákok felhasználóneveit akik az általunk létrehozott tananyagokhoz szeretnének hozzáférni. A felhasználónév mellett található checkbox bepipálásával, majd a Hozzáférés megadása gombra kattintva engedélyezhetjük a tananyagok és tesztek megtekintését.
Adminisztrátor jogosultságú felhasználó minden tananyaghoz tartozó kérést jóvá tud hagyni, nem csak az általa létrehozottakhoz tartozót.

# 8. Fogalomszótár

E-learning: Egy számítógépes hálózaton elérhető képzési forma, amely a tanítási-tanulási folyamatot teljes egészében a digitális térbe ülteti.

Kurzus: Tanórák vagy előadások sora, amelyeket befejezve a tanuló új ismereteket és képességeket szerez, amiről oklevelet kap.

# 9. Vágyálomrendszer

A projekt célja egy webes tanulásszervezési rendszer, ahol az elérhető funkciók felhasználói kategóriánként eltérőek, például egy diák számára más funkciók elérhetőek mint egy tanár számára, ezért a funkciók csak sikeres regisztráció és bejelentkezés után használhatóak.


Az alap felhasználókon felül kell egy magasabb rendű felhasználó, egy adminisztrátor, aki teljes hozzáféréssel rendelkezik a rendszerben. Az esetleges hibákat neki jelzik a felhasználók. Az admin korlátlanul módosíthatja, törölheti bármelyik kurzust valamint a felhasználók adatait is módosíthatja vagy adhat hozzá új felhasználót.
A többi felhasználó jelentkezhet a kurzusokra de nem módosíthatja azt, csak a sajátjait. 


Adminisztrátori vagy tanári jogosultsági szinttel a felhasználók létrehozhatnak kurzusokat amelyekben tananyagokat, teszteket, feladatokat tölthetnek fel. A kurzusok létrehozása során, készíthetnek komplexebb vagy szimplább kurzusokat, függően attól, hogy milyen céllal, milyen témával fog rendelkezni.
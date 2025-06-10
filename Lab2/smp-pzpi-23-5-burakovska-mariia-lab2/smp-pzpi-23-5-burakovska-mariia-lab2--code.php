<?php
mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'uk_UA.UTF-8');

class GroceryStore {
    private $products = [
        1 => ['name' => 'Молоко пастеризоване', 'price' => 12],
        2 => ['name' => 'Хліб чорний',       'price' => 9],
        3 => ['name' => 'Сир білий',         'price' => 21],
        4 => ['name' => 'Сметана 20%',       'price' => 25],
        5 => ['name' => 'Кефір 1%',          'price' => 19],
        6 => ['name' => 'Вода газована',     'price' => 18],
        7 => ['name' => 'Печиво "Весна"',    'price' => 14]
    ];

    private $cart     = [];
    private $userName = '';
    private $userAge  = 0;

    public function run() {
        while (true) {
            $this->showMainMenu();
            $command = $this->getInput();

            switch ($command) {
                case '1':
                    $this->selectProducts();
                    break;
                case '2':
                    $this->showReceipt();
                    break;
                case '3':
                    $this->setupProfile();
                    break;
                case '0':
                    echo "До побачення!\n";
                    return;
                default:
                    $this->showError();
            }
        }
    }

    private function showMainMenu() {
        echo "\n################################\n";
        echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
        echo "################################\n";
        echo "1 Вибрати товари\n";
        echo "2 Отримати підсумковий рахунок\n";
        echo "3 Налаштувати свій профіль\n";
        echo "0 Вийти з програми\n";
        echo "Введіть команду: ";
    }

    private function showError() {
        echo "ПОМИЛКА! Введіть правильну команду\n";
        echo "1 Вибрати товари\n";
        echo "2 Отримати підсумковий рахунок\n";
        echo "3 Налаштувати свій профіль\n";
        echo "0 Вийти з програми\n";
        echo "Введіть команду: ";
    }

    private function selectProducts() {
        while (true) {
            $this->showProductList();
            $productId = $this->getInput();

            if ($productId === '0') {
                break;
            }

            if (!isset($this->products[$productId])) {
                echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n\n";
                continue;
            }

            $product = $this->products[$productId];
            echo "Вибрано: " . $product['name'] . "\n";
            echo "Введіть кількість, штук: ";

            $quantity = (int)$this->getInput();

            if ($quantity < 0 || $quantity >= 100) {
                echo "ПОМИЛКА! Кількість повинна бути від 0 до 99\n";
                continue;
            }

            if ($quantity === 0) {
                if (isset($this->cart[$productId])) {
                    unset($this->cart[$productId]);
                    echo "ВИДАЛЯЮ З КОШИКА\n";
                }
                if (empty($this->cart)) {
                    echo "КОШИК ПОРОЖНІЙ\n";
                }
            } else {
                $this->cart[$productId] = [
                    'name'     => $product['name'],
                    'price'    => $product['price'],
                    'quantity' => $quantity
                ];
                $this->showCart();
            }
        }
    }

    private function showProductList() {
        echo "\n№  НАЗВА                       ЦІНА\n";
        foreach ($this->products as $id => $product) {
            printf("%-2d %-27s %d\n", $id, $this->mb_str_pad($product['name'], 27), $product['price']);
        }
        echo "   -----------\n";
        echo "0  ПОВЕРНУТИСЯ\n";
        echo "Виберіть товар: ";
    }

    private function showCart() {
        echo "У КОШИКУ:\n";
        echo "НАЗВА                       КІЛЬКІСТЬ\n";
        foreach ($this->cart as $item) {
            printf("%-27s %d\n", $this->mb_str_pad($item['name'], 27), $item['quantity']);
        }
    }

private function showReceipt() {
    if (empty($this->cart)) {
        echo "\nКОШИК ПОРОЖНІЙ\n";
        return;
    }

    echo "\n№  НАЗВА                       ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
    $total   = 0;
    $counter = 1;

    foreach ($this->cart as $item) {
        $cost  = $item['price'] * $item['quantity'];
        $total += $cost;

        $name = $this->mb_str_pad($item['name'], 27);
        printf("%-2d %s %5d %9d %9d\n",
            $counter++, $name, $item['price'], $item['quantity'], $cost);
    }

    echo "РАЗОМ ДО СПЛАТИ: $total\n";
}

    function setupProfile()
{
    global $userName, $userAge, $MIN_AGE, $MAX_AGE;

    while (true) {
        $name = trim(readline("Ваше ім'я: "));

        if (empty($name) || !preg_match('/[a-zA-Zа-яА-ЯіІїЇєЄґҐ]/u', $name)) {
            echo "ПОМИЛКА! Ім'я повинно містити хоча б одну літеру\n";
            continue;
        }

        $userName = $name;
        break;
    }

    while (true) {
      echo "Ваш вік: ";
        $age = (int)$this->getInput();

        if ($age < 7 || $age > 150) {
            echo "ПОМИЛКА! Вік повинен бути від 7 до 150 років\n";
            return;
        }
        $userAge = $age;
        break;
    }

    echo "\n";
    echo "Профіль збережено: " . $name . ", " . $age . " років\n";
    echo "\n";
}

    private function getInput(): string
    {
        $raw = fgets(STDIN);
        if ($raw === false) {
            return '';
        }

        $trimmed = trim($raw);
        return mb_convert_encoding($trimmed, 'UTF-8', 'CP866');
    }

    private function mb_str_pad($input, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT, $encoding = "UTF-8")
    {
        $length  = mb_strlen($input, $encoding);
        $padding = $pad_length - $length;
        if ($padding <= 0) return $input;

        switch ($pad_type) {
            case STR_PAD_RIGHT:
                return $input . str_repeat($pad_string, $padding);
            case STR_PAD_LEFT:
                return str_repeat($pad_string, $padding) . $input;
            case STR_PAD_BOTH:
                $left  = floor($padding / 2);
                $right = $padding - $left;
                return str_repeat($pad_string, $left) . $input . str_repeat($pad_string, $right);
            default:
                return $input;
        }
    }
}

$store = new GroceryStore();
$store->run();

<html>
</body><?php
 

class TransactionDemo {
 
    const DB_HOST = 'localhost';
    const DB_NAME = 'credit';
    const DB_USER = 'root';
    const DB_PASSWORD = 'vipanchi';
 
   
    public function __construct() {
        // open database connection
        $conStr = sprintf("mysql:host=%s;dbname=%s", self::DB_HOST, self::DB_NAME);
        try {
            $this->pdo = new PDO($conStr, self::DB_USER, self::DB_PASSWORD);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
 
    /**
     * PDO instance
     * @var PDO 
     */
    private $pdo = null;
 
    /**
     * Transfer money between two accounts
     * @param int $from
     * @param int $to
     * @param float $amount
     * @return true on success or false on failure.
     */
    public function transfer($from, $to, $Current_Credit) {
 
        try {
            $this->pdo->beginTransaction();
 
            // get available amount of the transferer account
            $sql = 'SELECT Current_Credit FROM vcredit WHERE Ac_no=:from';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(":from" => $from));
            $availableAmount = (int) $stmt->fetchColumn();
            $stmt->closeCursor();
 
            if ($availableAmount < $Current_Credit) {
                echo 'Insufficient amount to transfer';
                return false;
            }
			
            // deduct from the transferred account
            $sql_update_from = 'UPDATE vcredit
 SET Current_Credit = Current_Credit - :Current_Credit
 WHERE Ac_no = :from';
            $stmt = $this->pdo->prepare($sql_update_from);
            $stmt->execute(array(":from" => $from, ":Current_Credit" => $Current_Credit));
            $stmt->closeCursor();
 
            // add to the receiving account
            $sql_update_to = 'UPDATE vcredit
                                SET Current_Credit = Current_Credit + :Current_Credit
                                WHERE Ac_no = :to';
            $stmt = $this->pdo->prepare($sql_update_to);
            $stmt->execute(array(":to" => $to, ":Current_Credit" => $Current_Credit));
 
            // commit the transaction
            $this->pdo->commit();
 
            echo 'The amount has been transferred successfully';
			
 
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die($e->getMessage());
        }
    }
 
    /**
     * close the database connection
     */
    public function __destruct() {
        // close the database connection
        $this->pdo = null;
    }
 
}
$obj = new TransactionDemo();

$x = $_GET["f"]; 
$x=(int)$x;

$y = $_GET["t"]; 
$y=(int)$y;

$z = $_GET["cc"]; 
$z=(int)$z;

// transfer 30K from from account 1 to 2
$obj->transfer($x,$y,$z);
?>
 <a href="creditdb.php">click here to view all users</a>
 </body>
 </html>

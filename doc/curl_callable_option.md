cURL Callable Option
====================

Abstract classes usable for cURL options accepting callables are provided

| cURL option              | class                                            |
|--------------------------|--------------------------------------------------|
| CURLOPT_HEADERFUNCTION   | Tabellarium\Curl\CallableOption\HeaderCallable   |
| CURLOPT_PROGRESSFUNCTION | Tabellarium\Curl\CallableOption\ProgressCallable |
| CURLOPT_READFUNCTION     | Tabellarium\Curl\CallableOption\ReadCallable     |
| CURLOPT_WRITEFUNCTION    | Tabellarium\Curl\CallableOption\WriteCallable    |

These are abstract classes and provide abstract ::invoke() method that:

- has signature coresponding to what cURL expects from the function
- instead of cURL resource will receive an instance of **Tabellarium\Curl\CurlHandleInterface**

You can utilize these classes as starting point of implementing callable objects you want to use to pass to cURL.
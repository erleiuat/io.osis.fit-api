# API-Error Codes
A list of current used error codes.


---
### 00xx - General

#### 00<span style="text-decoration:underline">01</span>
General / Unknown errors
- HTTP-Code: <b>500</b>


---
### 01xx - Router

##### 01<span style="text-decoration:underline">01</span>
Version mismatch: `api.test.com/v4/call` = Version 4 not found.
- HTTP-Code: <b>404</b>

##### 01<span style="text-decoration:underline">02</span>
Route not found.
- HTTP-Code: <b>404</b>


---
### 02xx - Database

##### 02<span style="text-decoration:underline">01</span>
Connect Error
- HTTP-Code: <b>500</b>

##### 02<span style="text-decoration:underline">02</span>
Statement-Prepare error
- HTTP-Code: <b>500</b>

##### 02<span style="text-decoration:underline">03</span>
Statement-Bind error
- HTTP-Code: <b>500</b>

##### 02<span style="text-decoration:underline">04</span>
Statement-Execute error
- HTTP-Code: <b>500</b>


---
### 03xx - Core

##### 03<span style="text-decoration:underline">01</span>
Request-Body contains invalid JSON
- HTTP-Code: <b>400</b>


---
### 04xx - Validation
Errors during input validation

#### 04<span style="text-decoration:underline">1x</span> - Numbers
Errors concerning a received number value

##### 041<span style="text-decoration:underline">0</span>
Required value is missing/null
- HTTP-Code: <b>422</b>

##### 041<span style="text-decoration:underline">1</span>
Value is not numeric.
- HTTP-Code: <b>422</b>

##### 041<span style="text-decoration:underline">2</span>
Value is too big or too small.
- HTTP-Code: <b>422</b>


#### 04<span style="text-decoration:underline">2x</span> - Strings
Errors concerning a received string value

##### 042<span style="text-decoration:underline">0</span>
Required value is missing/null
- HTTP-Code: <b>422</b>

##### 042<span style="text-decoration:underline">1</span>
Value is too long or too short.
- HTTP-Code: <b>422</b>

*To be continued...*
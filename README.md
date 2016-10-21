# php-buffer

#### Access to binary data
This is classes defines methods for **reading and writing** values of all primitive types. Primitive values are translated to (or from) sequences of bytes according to the buffer's current byte order, which may be retrieved and modified via the order methods. The initial order of a byte buffer is always Buffer::BIG_ENDIAN.
 
### Requirements
* PHP >= 5.4 (64 bit)

### Installation
```bash
composer require nelexa/buffer
```

### Documentation

Class `\Nelexa\Buffer` is abstract and base methods for all other buffers.

Initialize buffer as string.
```php
$buffer = new \Nelexa\StringBuffer();
// or
$buffer = new \Nelexa\StringBuffer($text);
```

Initialize buffer as file.
```php
$buffer = new \Nelexa\FileBuffer($filename);
```

Initialize buffer as memory resource.
```php
$buffer = new \Nelexa\MemoryReourceBuffer();
// or
$buffer = new \Nelexa\MemoryReourceBuffer($text);
```

Initialize buffer as stream resource.
```php
$fp = fopen('php://temp', 'w+b');
// or
$buffer = new \Nelexa\ResourceBuffer($fp);
```

Set read only buffer
```php
$buffer->setReadOnly(true);
```

Checking the possibility of recording in the buffer
```php
$boolValue = $buffer->isReadOnly();
```

Modifies this buffer's byte order, either Buffer::BIG_ENDIAN or Buffer::LITTLE_ENDIAN
```php
$buffer->setOrder(\Nelexa\Buffer::LITTLE_ENDIAN);
```

Get buffer's byte order
```php
$byteOrder = $buffer->order();
```

Set buffer position.
```php
$buffer->setPosition($position);
```

Get buffer position.
```php
$position = $buffer->position();
```

Get buffer size.
```php
$size = $buffer->size();
```

Rewinds this buffer. The position is set to zero.
```php
$buffer->rewind();

// example
$buffer->insertString('Test value');
assert($buffer->position() === 10);
$buffer->rewind();
assert($buffer->position() === 0);
assert($buffer->size() === 10);
```

Flips this buffer. The limit is set to the current position and then the position is set to zero.
```php
$buffer->flip();

// example
$buffer->insertString('Test value');
assert($buffer->position() === 10);
$buffer->setPosition(5);
$buffer->flip();
assert($buffer->position() === 0);
assert($buffer->size() === 5);
```

Returns the number of elements between the current position and the limit.
```php
$remaining = $buffer->remaining();

// example
$buffer->insertString('Test value');
assert($buffer->position() === 10);
$buffer->setPosition(7);
assert($buffer->remining() === 10 - 7);
```

Tells whether there are any elements between the current position and the limit. True if, and only if, there is at least one element remaining in this buffer
```php
$boolValue = $buffer->hasRemining();

// example
$buffer->insertString('Test value');
assert($buffer->position() === 10);
assert($buffer->hasRemining() === false);
$buffer->setPosition(9);
assert($buffer->hasRemining() === true);
```

Close buffer and release resources
```php
$buffer->close();
```


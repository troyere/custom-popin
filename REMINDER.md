REMINDER
========

Sources
-------

- http://blog.overnetcity.com/tag/react-js/
- http://davidtucker.net/articles/automating-with-grunt/
- https://github.com/ServiceStack/redis-windows
- http://www.sitepoint.com/an-introduction-to-redis-in-php-using-predis/
- https://github.com/snc/SncRedisBundle/blob/master/Resources/doc/index.md
- https://facebook.github.io/react/docs/tutorial.html

Notes
-----

Setting field value by using "ref" attribute :
```JavaScript
React.findDOMNode(this.refs.myFieldName).value = myNewValue;
```

Get field value by using "ref" attribute :
```JavaScript
React.findDOMNode(this.refs.myFieldName).value.trim()
```

**Important** ! This trick cannot work for now :
```JavaScript
handleChangeField: function(event) {
    var state = { formDirty: true };
    state[event.target.name] = event.target.value;
    this.setState(state);
},
```

How to set the style attribute :
```JavaScript
style={marginRight: spacing + 'em'}
```
export default {

  methods:{
    objectToFormData (obj, fd, pre) {
      fd = fd || new FormData();

      if (this.isUndefined(obj)) {
        return fd
      } else if (this.isArray(obj)) {
        obj.forEach( (value) => {
          var key = pre + '[]';

          this.objectToFormData(value, fd, key);
        });
      } else if (this.isObject(obj) && !this.isFile(obj) && !this.isDate(obj)) {
        Object.keys(obj).forEach( (prop)=> {
          var value = obj[prop];

          if (this.isArray(value)) {
            while (prop.length > 2 && prop.lastIndexOf('[]') === prop.length - 2) {
              prop = prop.substring(0, prop.length - 2);
            }
          }

          var key = pre ? (pre + '[' + prop + ']') : prop;

          this.objectToFormData(value, fd, key);
        });
      } else {
        fd.append(pre, obj);
      }

      return fd
    },
    isUndefined (value) {
      return value === undefined
    },
    isDate (value) {
      return value instanceof Date
    },
    isFile (value) {
      return value instanceof File
    },
    isArray (value) {
      return Array.isArray(value)
    },
    isObject (value) {
      return value === Object(value)
    }

  }
}

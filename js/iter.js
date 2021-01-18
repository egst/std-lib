/**
    @template T
    @interface {Object} Iterable
    @property {() => {next: T, done: bool}} next
*/

/**
    A lazily evaluated iterable from any iterable.
    May be used to avoid copies of arrays.
    @template T
    @param {Iterable<T>} a
    @returns {Iterable<T>}
*/
export const lazy = function * (a) {
    for (const e of a)
        yield e
}

/**
    Iterable range [from, to).
    @param {number} from
    @param {number} to
    @param {number} step
    @returns {Iterable<number>}
*/
export const range = function * (from, to, step = 1) {
    for (let i = from; i < to; i += step)
        yield i
}

/**
    Iterable range containing center in the [from, to) bounds.
    @param {number} from
    @param {number} center
    @param {number} to
    @param {number} step
    @returns {Iterable<number>}
*/
export const rangeAround = function * (from, center, to, step = 1) {
    for (const i of range(center - Math.floor(center - from / step) * step, center, step))
        yield i
    for (const i of range(center, to, step))
        yield i
}

/**
    Iterable "zip" containing pairs from two iterables sequentially.
    @template T
    @template U
    @param {Iterable<T>} a
    @param {Iterable<U>} b
    @returns {Iterable<[T, U]>}
*/
export const zip = function * (a, b) {
    const ia = a[Symbol.iterator]()
    const ib = b[Symbol.iterator]()
    for (let ea = ia.next(), eb = ib.next(); !ea.done && !eb.done; ea = ia.next(), eb = ib.next())
        yield [ea.value, eb.value]
}

/**
    Iterable zip of the given iterable and a corresponding numeric indices range.
    @template T
    @param {Iterable<T>} a
    @returns {Iterable<[number, T]>}
*/
export const enumerate = a => zip(range(0, a.length), a)

/**
    Discrete samples from a function in the given range.
    @template T
    @param {number => T} f
    @param {number} from
    @param {number} to
    @param {number} step
    @returns {Iterable<T>}
*/
export const sample = function * (f, from, to, step) {
    for (const i of range(from, to, step))
        yield [i, f(i)]
}

/**
    Repeat single value in an iterable. Infinite iterable when no count provided.
    @template T
    @param {T} x
    @param {number} [count = Infinity]
    @returns {Iterable<T>}
*/
export const repeat = function * (x, count = Infinity) {
    for (const _ of range(0, count))
        yield x
}

/**
    Repeat constant function value in an iterable. Infinite iterable when no count provided.
    @template T
    @param {() => T} x
    @param {number} [count = Infinity]
    @returns {Iterable<T>}
*/
export const repeatFn = function * (fn, count = Infinity) {
    for (const _ of range(0, count))
        yield fn()
}

/**
    Last array element.
    @template T
    @param {T[]} a
    @returns {T}
*/
export const last = a => a[a.length - 1]

import { useRef, useEffect, useState } from 'react';
import { motion, useInView } from 'framer-motion';

export default function BlurText({
    text = '',
    delay = 200,
    className = '',
    animateBy = 'words', // 'words' or 'letters'
    direction = 'top', // 'top' or 'bottom'
    as: Component = 'div',
}) {
    const ref = useRef(null);
    const isInView = useInView(ref, { once: true, margin: '-50px' });

    const defaultVariants = {
        hidden: { 
            filter: 'blur(10px)', 
            opacity: 0, 
            y: direction === 'top' ? -20 : 20 
        },
        visible: { 
            filter: 'blur(0px)', 
            opacity: 1, 
            y: 0 
        },
    };

    const elements = animateBy === 'words' ? text.split(' ') : text.split('');

    return (
        <Component ref={ref} className={className} style={{ margin: 0, display: 'flex', flexWrap: 'wrap', gap: animateBy === 'words' ? '0.25em' : '0px' }}>
            {elements.map((element, index) => (
                <motion.span
                    key={index}
                    initial="hidden"
                    animate={isInView ? 'visible' : 'hidden'}
                    variants={defaultVariants}
                    transition={{
                        duration: 0.8,
                        delay: index * (delay / 1000),
                        ease: [0.25, 0.4, 0.25, 1], // Cubic bezier for smooth easing
                    }}
                    style={{ display: 'inline-block', whiteSpace: 'pre' }}
                >
                    {element === ' ' ? '\u00A0' : element}
                </motion.span>
            ))}
        </Component>
    );
}
